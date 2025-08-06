<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Client\Response;

class InstagramService
{
    protected $apiBaseUrl = 'https://graph.facebook.com/v23.0';

    public function getRecentPosts(string $userAccessToken): array
    {
        try {
            // --- Obtener las Páginas del Usuario ---
            $pagesResponse = Http::get("{$this->apiBaseUrl}/me/accounts", [
                'access_token' => $userAccessToken,
            ]);

            if ($pagesResponse->failed()) {
                throw new \Exception('No se pudieron obtener las páginas de Facebook.');
            }

            $pages = $pagesResponse->json()['data'];
            if (empty($pages)) {
                Log::warning('El usuario no administra ninguna página de Facebook.');
                return []; 
            }
            Log::info('Páginas obtenidas de Facebook para el usuario.', [
                'count' => count($pages),
                'pages' => $pages
            ]);

            // TODO: darle al DJ la opción de elegir cuál usar si administra varias.
            $pageId = $pages[0]['id'];
            $pageAccessToken = $pages[0]['access_token'];

            $igAccountResponse = Http::get("{$this->apiBaseUrl}/{$pageId}", [
                'fields' => 'instagram_business_account',
                'access_token' => $pageAccessToken,
            ]);

            if ($igAccountResponse->failed() || !isset($igAccountResponse->json()['instagram_business_account'])) {
                throw new \Exception('Esta página de Facebook no tiene una cuenta de Instagram de empresa vinculada.');
            }

            $instagramBusinessAccountId = $igAccountResponse->json()['instagram_business_account']['id'];

            // --- Obtener los Posts de Instagram ---
            $mediaResponse = Http::get("{$this->apiBaseUrl}/{$instagramBusinessAccountId}/media", [
                'fields' => 'id,caption,media_type,media_url,thumbnail_url,permalink',
                'access_token' => $pageAccessToken,
            ]);

            if ($mediaResponse->failed()) {
                throw new \Exception('No se pudieron obtener los posts de Instagram.');
            }

            return $mediaResponse->json()['data'];

        } catch (\Exception $e) {
            Log::error('Error al obtener posts de Instagram', [
                'message' => $e->getMessage(),
            ]);
            return [];
        }
    }
    
    public function subscribeToWebhook(string $userAccessToken): Response
    {
        try {
            $pagesResponse = Http::get("{$this->apiBaseUrl}/me/accounts", [
                'access_token' => $userAccessToken,
            ]);
            
            if ($pagesResponse->failed()) {
                throw new \Exception('No se pudieron obtener las páginas de Facebook para la suscripción.');
            }

            $pages = $pagesResponse->json()['data'];
            if (empty($pages)) {
                Log::warning('El usuario no administra ninguna página, no se puede suscribir.');
                // Devolvemos la respuesta original para que el controlador pueda manejarla
                return $pagesResponse;
            }

            $pageId = $pages[0]['id'];
            $pageAccessToken = $pages[0]['access_token'];

            $response = Http::withToken($pageAccessToken)
                ->post("{$this->apiBaseUrl}/{$pageId}/subscribed_apps", [
                    'subscribed_fields' => ['feed']
                ]);

            // Devolvemos el objeto de respuesta original
            return $response;

        } catch (\Exception $e) {
            Log::error('Excepción al suscribirse al webhook de Instagram: ' . $e->getMessage());
            throw $e;
        }
}

}


