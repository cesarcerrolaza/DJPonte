<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessTip;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class StripeController extends Controller
{
    public function connect()
    {
        $user = Auth::user();

        // Si el usuario ya tiene una cuenta de Stripe, redirigir al dashboard
        if ($user->stripe_account_id) {
            return redirect()->route('dashboard')->with('info', 'Ya tienes una cuenta de Stripe conectada.');
        }

        Stripe::setApiKey(config('cashier.secret'));

        // Crear una cuenta de Stripe Express para el usuario
        $account = Account::create([
            'type' => 'express',
            'country' => 'ES', // O el país que corresponda
            'email' => $user->email,
            'capabilities' => [
                'card_payments' => ['requested' => true],
                'transfers' => ['requested' => true],
            ],
        ]);

        // Guardar el ID de la cuenta en el usuario
        $user->stripe_account_id = $account->id;
        $user->save();

        // Crear un enlace de onboarding
        $accountLink = AccountLink::create([
            'account' => $account->id,
            'refresh_url' => route('stripe.connect'),
            'return_url' => route('stripe.return'),
            'type' => 'account_onboarding',
        ]);

        // Redirigir al usuario a la página de onboarding de Stripe
        return redirect()->away($accountLink->url);
    }

    // Maneja los webhooks de Stripe
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
