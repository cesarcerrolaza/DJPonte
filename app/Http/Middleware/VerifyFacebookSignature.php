<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Log;

class VerifyFacebookSignature
{
    public function handle(Request $request, Closure $next)
    {
        $signature = $request->header('X-Hub-Signature-256');
        Log::info('Verifying Facebook signature', [
            'signature' => $signature,
            'request_body' => $request->getContent(),
        ]);
        if (!$signature) {
            // Aborta si no hay firma
            Log::warning('Signature not found in request headers.');
            abort(403, 'Signature not found.');
        }

        $hash = hash_hmac('sha256', $request->getContent(), config('services.instagram.app_secret'));

        if (!hash_equals('sha256='.$hash, $signature)) {
            Log::warning('Invalid signature', [
                'expected' => 'sha256='.$hash,
                'received' => $signature,
            ]);
            // Aborta si la firma no coincide
            abort(403, 'Invalid signature.');
        }

        return $next($request);
    }
}