<?php

namespace App\Http\Controllers;

use App\Models\Djsession;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Container\Attributes\Log;

class DjsessionController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');  // Asegura que solo usuarios autenticados accedan
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $djName = null;
        $djAvatar = null;

        if ($user->role === 'dj') {
            $djsessionActive = $user->djsessionActive;
            $dj = $user;
            $djAvatar = $dj->profile_photo_path;
            $djName = $dj->name;
            $djsessions = $user->djsessions()->where('active', false)->get();
            return view('djsession.index', [
                'djsessions' => $djsessions,
                'djsessionActive' => $djsessionActive,
                'djName' => $djName,
                'djAvatar' => $djAvatar,
                'role' => $user->role,
            ]);
        } else {
            // Si es un usuario normal, listar todas las sesiones activas disponibles para unirse
            $djsessionActive = $user->djsessionActive;
            if($djsessionActive){
                $dj = $djsessionActive->dj; // Esto está bien
                $djAvatar = $dj->profile_photo_path;
                $djName = $dj->name;
            }
            return view('djsession.index', [
                'djsessionActive' => $djsessionActive,
                'djName' => $djName,
                'djAvatar' => $djAvatar,
                'role' => $user->role,
            ]);
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

    public function store(Request $request)
    {
        // Solo los DJs pueden almacenar sesiones
        $user = $request->user();
        if ($user->role !== 'dj') {
            abort(403);
        }

        // Validar datos de entrada
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'active' => 'boolean',
            'description' => 'nullable|string|max:1000',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Si la nueva sesión está marcada como activa, desactivar cualquier otra sesión activa del DJ
        if (!empty($data['active']) && $data['active']) {
            $user->djsessions()
                 ->where('active', true)
                 ->update(['active' => false]);
        } else {
            // Asegurar que 'active' esté definido (falso por defecto si no se envió)
            $data['active'] = false;
        }

        // Crear la sesión asociada al DJ autenticado
        $session = new Djsession($data);
        $session->user_id = $user->id;
        $session->save();

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
        return view('djsessions.show', ['session' => $djsession]);
    }

    public function edit(Request $request, Djsession $djsession)
    {
        $user = $request->user();
        // Solo el DJ dueño de la sesión puede editarla
        if ($user->role !== 'dj' || $djsession->user_id !== $user->id) {
            abort(403);
        }
        return view('djsessions.edit', ['session' => $djsession]);
    }

    public function update(Request $request, Djsession $djsession)
    {
        $user = $request->user();
        // Solo el DJ dueño de la sesión puede actualizarla
        if ($user->role !== 'dj' || $djsession->user_id !== $user->id) {
            abort(403);
        }

        // Validar datos de entrada
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'active' => 'boolean',
            'description' => 'nullable|string|max:1000',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Si se está activando esta sesión, desactivar las otras sesiones activas del DJ
        if (!empty($data['active']) && $data['active']) {
            $user->djsessions()
                 ->where('active', true)
                 ->where('id', '!=', $djsession->id)
                 ->update(['active' => false]);
        } else {
            // Si no se envió 'active' (checkbox desmarcado), establecer como falso
            $data['active'] = false;
        }

        $djsession->update($data);
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

        // Desconectar a los usuarios asociados antes de eliminar la sesión (si aplica)
        User::where('djsession_id', $djsession->id)->update(['djsession_id' => null]);

        $djsession->delete();
        return redirect()->route('djsessions.index')
                         ->with('success', 'Sesión eliminada correctamente.');
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
                             ->with('error', 'No se puede unir a una sesión inactiva.');
        }
        // Impedir unirse si el usuario ya está en otra sesión activa
        if ($user->djsession_id) {
            return redirect()->route('djsessions.index')
                             ->with('error', 'Ya estás unido a otra sesión.');
        }

        // Unir al usuario a la sesión activa
        $user->djsession_id = $djsession->id;
        $user->save();
        return redirect()->route('djsessions.show', $djsession)
                         ->with('success', 'Te has unido a la sesión.');
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
        // Aquí puedes implementar la lógica para buscar sesiones de DJ
        // Puedes usar el modelo Djsession para realizar consultas a la base de datos
        $query = $request->input('query');
        $djsessions = Djsession::where('name', 'LIKE', "%$query%")->get();
        return view('djsession.search', ['djsessions' => $djsessions]);
    }

    public function requestSong($id)
    {
        // Aquí puedes implementar la lógica para solicitar una canción en una sesión de DJ
        // Validar y agregar la solicitud de canción a la base de datos
        return redirect()->route('djsession.show', ['id' => $id]);
    }

    public function voteSong($id)
    {
        // Aquí puedes implementar la lógica para votar por una canción en una sesión de DJ
        // Validar y actualizar el voto en la base de datos
        return redirect()->route('djsession.show', ['id' => $id]);
    }



}
