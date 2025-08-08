<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Services\InstagramService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\SocialPost;
use App\Models\SocialAccount;



class SocialController extends Controller
{
    protected $instagramService;

    protected $tokenScopes = [
        'pages_show_list',
        'instagram_basic',
        'pages_read_engagement',
        'business_management',
        'pages_manage_metadata', // <-- Permiso para suscribirse a 'feed'
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
                try{
                    $response = $this->instagramService->subscribeToWebhook($socialAccount->access_token);
                    if ($response->failed()) {
                        Log::error('Error al suscribir la página al webhook: ' . $response->body());
                    } else {
                        Log::info('Suscripción al webhook realizada con éxito.');
                    }
                }
                catch (\Exception $e) {
                    Log::error('Excepción al suscribir al webhook: ' . $e->getMessage());
                    return redirect()->route('socialManagement')->with('error', 'Error al suscribir al webhook de Instagram. Por favor, inténtalo de nuevo más tarde.');
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


    
    /**
     * Maneja la solicitud de eliminación de datos de usuario enviada por Meta.
     */
    public function handleDataDeletion(Request $request)
    {
        $signedRequest = $request->input('signed_request');

        if (!$signedRequest) {
            Log::warning('Data deletion request received without a signed_request.');
            return response()->json(['error' => 'Invalid request'], 400);
        }

        // Decodificamos la petición firmada para obtener los datos
        $data = $this->parseSignedRequest($signedRequest);

        if (!$data) {
            Log::error('Failed to parse signed_request for data deletion.');
            return response()->json(['error' => 'Invalid signed_request'], 400);
        }

        $userId = $data['user_id'];

        // --- Lógica de Eliminación ---
        // Buscamos la cuenta social vinculada a este ID de usuario de la plataforma
        $socialAccount = SocialAccount::where('account_id', $userId)->first();

        if ($socialAccount) {
            // Obtenemos el usuario de nuestra aplicación a través de la relación
            $user = $socialAccount->user;

            if ($user) {
                $user->delete();
                Log::info("User data deleted successfully for platform user_id: {$userId}");
            }
        }

        // --- Respuesta de Confirmación para Meta ---
        // Generamos un código de confirmación y una URL para que Meta rastree el estado.
        $confirmationCode = 'user_deleted_' . $userId;
        $statusUrl = route('data-deletion.status', ['confirmation_code' => $confirmationCode]);

        return response()->json([
            'url' => $statusUrl,
            'confirmation_code' => $confirmationCode,
        ]);
    }

    /**
     * Decodifica y verifica la petición firmada de Meta.
     *
     * @param string $signedRequest
     * @return array|null
     */
    private function parseSignedRequest(string $signedRequest): ?array
    {
        list($encodedSig, $payload) = explode('.', $signedRequest, 2);

        // Decodificar la firma
        $sig = $this->base64UrlDecode($encodedSig);
        // Decodificar los datos
        $data = json_decode($this->base64UrlDecode($payload), true);

        // Verificar el algoritmo
        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
            Log::error('Unknown algorithm received in signed_request: ' . $data['algorithm']);
            return null;
        }

        // Verificar la firma
        $expectedSig = hash_hmac('sha256', $payload, config('services.facebook.app_secret'), true);

        if (hash_equals($expectedSig, $sig)) {
            return $data;
        }

        Log::warning('Bad Signed JSON signature!');
        return null;
    }

    /**
     * Función de ayuda para decodificar en base64 URL-safe.
     */
    private function base64UrlDecode(string $input): string
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * (Opcional) Una ruta para que Meta compruebe el estado de la eliminación.
     */
    public function showDeletionStatus(Request $request, $confirmation_code)
    {
        // Aquí podrías tener lógica para confirmar que la eliminación se completó.
        // Por ahora, una respuesta simple es suficiente.
        return response()->json([
            'status' => 'User data deletion complete.',
        ]);
    }

}
