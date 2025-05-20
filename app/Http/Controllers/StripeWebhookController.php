<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessTip;
use Illuminate\Support\Facades\Log;

// app/Http/Controllers/StripeWebhookController.php
class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        //Verifica la firma del webhook
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sigHeader, config('cashier.webhook.secret')
        );

        // Maneja el evento
        $type = $event->type;
        $intentId = $event->data->object->id;

        if ($type === 'checkout.session.completed') {
            ProcessTip::dispatch($intentId, 'paid');
        }
        
        return response()->json(['status' => 'ok'], 200);
    }
}
