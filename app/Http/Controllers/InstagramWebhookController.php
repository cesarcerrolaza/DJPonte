<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InstagramWebhookController extends Controller
{

    public function handle(Request $request)
    {
        Log::info('Instagram Webhook Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);
        // Verificación del webhook
        Log::info('New Instagram Webhook Request');
        if ($request->has('hub_mode') && $request->input('hub_mode') === 'subscribe' && $request->isMethod('get')) {
            Log::info('Instagram Webhook Verification Request', [
                'hub_verify_token' => $request->input('hub_verify_token'),
                'hub_challenge' => $request->input('hub_challenge'),
            ]);
            return $this->handleVerificationRequest($request);
        }

        // Notificaciones de eventos (como comentarios)
        if ($request->has('entry') && $request->isMethod('post')) {
            Log::info('Instagram Webhook Event Notification', [
                'entry' => $request->input('entry'),
            ]);
            return $this->handleEventNotification($request);
        }

        // Si no coincide con nada, devolver 400 Bad Request
        return response()->json(['error' => 'Invalid request'], 400);
    }


    public function handleVerificationRequest(Request $request)
    {
        if ($request->input('hub_verify_token') === config('services.meta.verify_token')) {
            return response($request->input('hub_challenge'), 200);
        };
        return response()->json(['error' => 'Verification failed'], 403);
    }

    public function handleEventNotification(Request $request)
    {
        foreach ($request->input('entry', []) as $entry) {
            foreach ($entry['changes'] as $change) {
                if ($change['field'] === 'comments') {
                    Log::info('Processing Instagram Comment Change', [
                        'change' => $change,
                    ]);
                    // Despachamos el trabajo a la cola
                    \App\Jobs\ProcessInstagramComment::dispatch($change);
                }
            }
        }
        // Respondemos a Meta
        return response()->json(['message' => 'Event received'], 200);
    }
}
