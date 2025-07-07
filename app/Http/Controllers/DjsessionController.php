<?php

namespace App\Http\Controllers;

use App\Models\Djsession;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\DjsessionRequest;
use App\Services\DjsessionService;
use Illuminate\Support\Facades\Log;
use App\Jobs\DeleteDjsessionJob;
use Illuminate\Support\Facades\Storage;

class DjsessionController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');  // Asegura que solo usuarios autenticados accedan
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->role;
        $djName = null;
        $djAvatar = null;

        if ($role === 'dj') {
            $djsessionActive = $user->djsessionActive;
            $djAvatar = $user->profile_photo_path;
            $djName = $user->name;
            $djsessions = $user->djsessions()->where('active', false)->get();
            return view('djsessions.index', compact('djsessions', 'djsessionActive', 'djName', 'djAvatar', 'role'));
        } else {
            // Si es un usuario normal, mostrar su sesión activa
            $djsessionActive = $user->djsessionActive;
            if($djsessionActive){
                $dj = $djsessionActive->dj;
                $djAvatar = $dj->profile_photo_path;
                $djName = $dj->name;
            }
            return view('djsessions.index', compact('djsessionActive', 'djName', 'djAvatar', 'role'));
        }
        
    }

    public function create(Request $request)
    {
        // Solo los DJs pueden crear nuevas sesiones
        if ($request->user()->role !== 'dj') {
            abort(403);
        }
        return view('djsessions.create');
    }

    public function store(DjsessionRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
    
        // Manejar la imagen de la sesión
        if ($request->hasFile('image')) {
            // Asignar nombre único a la imagen
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            // Almacenar la imagen en el disco público y obtener la ruta de almacenamiento
            $data['image'] = $request->file('image')->storeAs('djsessions', $imageName, 'public');
        }
        
        // Generar un código único para la sesión
        if (empty($data['code'])) {
            $data['code'] = strtoupper(uniqid('DJ-'));
        }

        $data['active'] = isset($data['active']) ? $data['active'] : false;

        if ($request->input('timeout_unit') === 'minutes') {
            $data['song_request_timeout'] = $data['song_request_timeout'] * 60;
            if ($data['song_request_timeout'] > 7200) {
                $data['song_request_timeout'] = 7200;
            }
        }
        // Crear la sesión asociada al DJ autenticado
        $session = new Djsession($data);
        $session->user_id = $user->id;
        $session->save();
    
        if ($data['active']) {
            app(DjsessionService::class)->activate($session, $user);
        }

        return redirect()->route('djsessions.index')
                         ->with('success', 'Sesión creada correctamente.');
    }

    public function show(Request $request, Djsession $djsession)
    {
        $user = $request->user();
        if ($user->role === 'dj') {
            // Si es DJ, solo puede ver sus propias sesiones
            if ($djsession->user_id !== $user->id) {
                abort(403);
            }
        } else {
            // Si es un usuario normal, solo permitir ver si la sesión está activa
            if (!$djsession->active) {
                abort(403);
            }
        }
        return view('djsessions.show', ['djsession' => $djsession]);
    }

    public function edit(Request $request, Djsession $djsession)
    {
        $user = $request->user();
        // Solo el DJ dueño de la sesión puede editarla
        if ($user->role !== 'dj' || $djsession->user_id !== $user->id) {
            abort(403);
        }
        return view('djsessions.edit', ['djsession' => $djsession]);
    }

    public function update(DjsessionRequest $request, Djsession $djsession)
    {
        $user = $request->user();
        $data = $request->validated();
        
        // Verificar si el usuario tiene permiso para actualizar esta sesión
        if ($user->id !== $djsession->user_id) {
            return redirect()->route('djsessions.index')
                             ->with('error', 'No tienes permiso para editar esta sesión.');
        }
        
        // Manejar la imagen de la sesión
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe y no es la imagen por defecto
            if ($djsession->image && $djsession->image !== Djsession::getDefaultImagePath()) {
                Storage::disk('public')->delete($djsession->image);
            }
 
            // Asignar nombre único a la nueva imagen
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            // Almacenar la imagen en el disco público y obtener la ruta de almacenamiento
            $data['image'] = $request->file('image')->storeAs('djsessions', $imageName, 'public');
        }

        if ($request->input('timeout_unit') === 'minutes') {
            $data['song_request_timeout'] = $data['song_request_timeout'] * 60;
            if ($data['song_request_timeout'] > 7200) {
                $data['song_request_timeout'] = 7200;
            }
        }
        
        // Actualizar la sesión
        $djsession->update($data);
        
        $data['active'] = isset($data['active']) ? $data['active'] : false;
        if ($data['active']) {
            app(DjsessionService::class)->activate($djsession, $user);
        } else {
            app(DjsessionService::class)->deactivate($djsession);
        }
        
        return redirect()->route('djsessions.index')
                        ->with('success', 'Sesión actualizada correctamente.');
    }

    public function destroy(Request $request, Djsession $djsession)
    {
        $user = $request->user();
        // Solo el DJ dueño de la sesión puede eliminarla
        if ($user->role !== 'dj' || $djsession->user_id !== $user->id) {
            abort(403);
        }

        app(DjsessionService::class)->preDelete($djsession);
        
        // Eliminar la imagen de la sesión si existe y no es la imagen por defecto
        DeleteDjsessionJob::dispatch($djsession)->delay(now()->addSeconds(10));
       // En tu método destroy()
        return redirect()->route('djsessions.index')
            ->with('success', 'La eliminación de la sesión ha sido programada y se completará en breve.');
    }

    public function join(Request $request, Djsession $djsession)
    {
        $user = $request->user();
        // Un DJ no puede unirse como participante a sesiones
        if ($user->role === 'dj') {
            abort(403);
        }
        // Impedir unirse si la sesión no está activa
        if (!$djsession->active) {
            return redirect()->route('djsessions.index')
                             ->with('flash.banner', 'No se puede unir a una sesión inactiva.')
                             ->with('flash.bannerStyle', 'danger');
        }
        app(DjsessionService::class)->join($djsession, $user);

        return redirect()->route('djsessions.index');
    } 
    
    public function leave(Request $request, Djsession $djsession)
    {
        $user = $request->user();
        // Un DJ no puede salir de una sesión como participante
        if ($user->role === 'dj') {
            abort(403);
        }
        // Desvincular al usuario de la sesión
        $user->djsession_id = null;
        $user->save();
        return redirect()->route('djsessions.index')
                         ->with('success', 'Has salido de la sesión.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $user = $request->user();
        $role = $user->role;
        if ($role === 'dj'){
            $djsessions = Djsession::with('dj')
                        ->where('user_id', $user->id)
                        ->where(function($queryBuilder) use ($query) {
                            $queryBuilder->where('name', 'LIKE', "%$query%")
                                         ->orWhere('code', 'LIKE', "%$query%");
                        })
                        ->get();
        }
        else{
            $djsessions = Djsession::with('dj')
                ->where('active', true)
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('name', 'LIKE', "%$query%")
                    ->orWhere('code', 'LIKE', "%$query%");
                })
                ->get();
            $currentDjsessionId = $user->djsession_id ?? null;
        }

        return view('djsessions.search', compact('djsessions', 'role', 'currentDjsessionId'));

    }

}
