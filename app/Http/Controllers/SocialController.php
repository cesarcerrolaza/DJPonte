<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Services\InstagramService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\SocialPost;



class SocialController extends Controller
{
    protected $instagramService;

    protected $tokenScopes = [
        'pages_show_list',
        'instagram_basic',
        'pages_read_engagement',
        'business_management',
        'pages_manage_metadata', // <-- Permiso para suscribirse a 'feed'
        'pages_messaging'      // <-- Permiso para suscribirse a 'messages'
    ];

    public function __construct(InstagramService $instagramService)
    {
        $this->instagramService = $instagramService;
    }

    /**
     * Conecta al usuario con Instagram para la autorización.
     */
    public function connectInstagram()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Buscamos si ya existe una cuenta de Instagram para este usuario.
        $socialAccount = $user->socialAccounts()->where('platform', 'instagram')->first();

        // Comprobamos si la cuenta existe y si el token sigue siendo válido.
        if ($socialAccount && $socialAccount->expires_at && Carbon::parse($socialAccount->expires_at)->isFuture()) {
            return redirect()->route('socialManagement');
        }
        
        return Socialite::driver('facebook')->scopes($this->tokenScopes)->redirect();
    }

    /**
     * Reconecta al usuario con Instagram para la autorización. Cuando el token ha caducado o se ha revocado.
     */
    public function reconnectInstagram()
    {     
        return Socialite::driver('facebook')->scopes($this->tokenScopes)->redirect();
    }

    /**
     * Maneja el callback de Instagram y guarda los datos en la tabla `social_accounts`.
     */
    public function handleInstagramCallback(Request $request)
    {
        Log::info('Callback de Instagram alcanzado.');

        if ($request->has('error') && $request->get('error') === 'access_denied') {
            Log::warning('El usuario ha denegado el acceso en la pantalla de consentimiento de Meta.');
            return redirect()->route('dashboard')->with('error', 'Has cancelado la conexión con Instagram.');
        }

        try {
            Log::info('Intentando obtener el usuario de Socialite...');
            
            $socialUser = Socialite::driver('facebook')->user();

            Log::info('Comprobando el usuario autenticado en Laravel...');
            
            $user = Auth::user();

            if (!$user) {
                Log::error('Error crítico: No se encontró un usuario autenticado en la sesión de Laravel. El usuario debe volver a iniciar sesión.');
                return redirect()->route('login')->with('error', 'Tu sesión ha expirado. Por favor, inicia sesión de nuevo antes de conectar tus redes.');
            }

            Log::info('Usuario de Laravel encontrado. ID: ' . $user->id . '. Intentando la operación de base de datos.');

            $dataToSave = [
                'username' => $socialUser->getNickname() ?? $socialUser->getName(),
                'access_token' => $socialUser->token,
                'refresh_token' => $socialUser->refreshToken,
                'expires_at' => $socialUser->expiresIn ? now()->addSeconds($socialUser->expiresIn) : null,
            ];
            Log::info('Datos que se pasarán a updateOrCreate:', $dataToSave);
            
            $user->socialAccounts()->updateOrCreate(
                [
                    'platform' => 'instagram',
                    'account_id' => $socialUser->getId(),
                ],
                $dataToSave
            );

            Log::info('Éxito: SocialAccount creada o actualizada correctamente para el usuario ID: ' . $user->id);

            $socialAccount = $user->socialAccounts()->where('platform', 'instagram')->first();
            if ($socialAccount) {
                $response = $this->instagramService->subscribeToWebhook($socialAccount->access_token);
                if ($response->failed()) {
                    Log::error('Error al suscribir la página al webhook: ' . $response->body());
                } else {
                    Log::info('Suscripción al webhook realizada con éxito.');
                }
            }
            return redirect()->route('socialManagement')->with('success', '¡Cuenta de Instagram conectada con éxito!');
        } catch (\Exception $e) {
            Log::error('Excepción capturada en el callback de Instagram.', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('dashboard')->with('error', 'Hubo un error inesperado al conectar con Instagram.');
        }
    }

    public function showPostGallery()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $socialAccount = $user->socialAccounts()->where('platform', 'instagram')->first();

        if (!$socialAccount) {
            Log::warning('El usuario ID ' . $user->id . ' no tiene una cuenta de Instagram conectada.');
            return redirect()->route('instagram.connect');
        }
        
        // Obtenemos los posts recientes de la API de Meta
        $posts = $this->instagramService->getRecentPosts($socialAccount->access_token);
        if (empty($posts)) {
            Log::info('No se encontraron posts recientes para el usuario ID ' . $user->id);
        }

        Log::info('Posts obtenidos de Instagram para el usuario ID ' . $user->id, [
            'count' => count($posts),
            'posts' => $posts
        ]);
        
        $activeDjSession = $user->djsessionActive;
        
        if($activeDjSession){
            $activePost = SocialPost::where('djsession_id', $activeDjSession->id ?? null)
                                    ->where('is_active', true)
                                    ->first();
            if ($activePost) {
                $response = $this->instagramService->subscribeToWebhook($socialAccount->access_token);
                if ($response->failed()) {
                    Log::error('Error al suscribir la página al webhook: ' . $response->body());
                } else {
                    Log::info('Suscripción al webhook realizada con éxito.');
                }
            }
        }
        else {
            $activePost = null;
        }
        return view('social-management', [
            'posts' => $posts,
            'activePost' => $activePost
        ]);
    }

    public function setMonitoredPost(Request $request)
    {
        $request->validate([
            'media_id' => 'required|string',
            'platform' => 'required|string',
            'caption' => 'nullable|string',
            'media_url' => 'nullable|string',
            'permalink' => 'nullable|string',
        ]);

        $user = Auth::user();
        $socialAccount = $user->socialAccounts()->where('platform', $request->platform)->firstOrFail();

        $activeDjSession = $user->djsessionActive;

        if ($activeDjSession) {
            SocialPost::where('djsession_id', $activeDjSession->id)
                ->update(['is_active' => false]);
        }
                
        // Crear el post si no existe, o actualizarlo si ya existía.
        $post = SocialPost::updateOrCreate(
            [
                'social_account_id' => $socialAccount->id,
                'media_id'          => $request->media_id,
            ],
            [
                'platform'      => $request->platform,
                'djsession_id'  => $activeDjSession ? $activeDjSession->id : null,
                'social_account_id' => $socialAccount->id,
                'is_active'     => $activeDjSession ? true : false, // Marcamos este como el activo
                'caption'       => $request->caption,
                'media_url'     => $request->media_url,
                'permalink'     => $request->permalink,
            ]
        );

        return back()->with('success', '¡Publicación seleccionada con éxito!');
    }
}
