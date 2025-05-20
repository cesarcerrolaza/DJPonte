<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Tip;
use App\Models\Donor;
use App\Models\SongRequest;
use App\Events\NewTip;
use Illuminate\Support\Facades\Log;

class TipController extends Controller
{

    public function success(Request $request)
    {
        Log::info('Entrando...');
        Stripe::setApiKey(config('cashier.secret'));

        // Se obtiene la sesión de Stripe
        $session_id = $request->get('session_id'); // Stripe puede devolverlo así
        if (!$session_id) {
            Log::info('No se recibió session_id en la solicitud de éxito.');
            return redirect()->route('index')->with('error', 'Hubo un error al procesar el pago. Espere confirmación e inténtalo de nuevo.');
        }
        $stripeSession = StripeSession::retrieve($session_id);

        // Buscar el Tip correspondiente
        $tip = Tip::where('stripe_session_id', $stripeSession->id)->first();

        if ($tip) {
            if($stripeSession->payment_status === StripeSession::PAYMENT_STATUS_PAID){
                // Actualizar el estado del Tip
                $tip->status = 'paid';
                $tip->save();
                // Actualizar el monto del donante
                $donor = Donor::where('user_id', $tip->user_id)->first();
                if ($donor) {
                    $donor->increment('amount', $tip->amount);
                } else {
                    $donor = Donor::create([
                        'user_id' => $tip->user_id,
                        'djsession_id' => $tip->djsession_id,
                        'amount' => $tip->amount,
                        'currency' => $tip->currency,
                    ]);
                }
                SongRequest::createSongRequest($tip->djsession_id, [
                    'title' => $tip->custom_title,
                    'artist' => $tip->custom_artist,
                    'songId' => $tip->song_id,
                ]);
                Log::info('Pago confirmado para la propina con ID: ' . $tip->id);
                broadcast(new NewTip($tip->id, $tip->djsession_id, $tip->user_id, 'paid', $tip->amount, $donor->amount));
                return redirect()->route('djsessions.index')->with('flash.banner', '¡Gracias por tu propina!')
                                                            ->with('flash.bannerStyle', 'success');
            }
            else {
                Log::info('El pago no fue confirmado para la propina con ID: ' . $tip->id);
                return redirect()->route('tip.id', ['id' => $tip->id]);
            }
        }
        Log::info('El pago no fue confirmado o la propina no se encontró.');
        return redirect()->route('index')->with('error', 'Hubo un error al procesar el pago. Espere confirmación e inténtalo de nuevo.');
    }

    public function cancel()
    {
        return redirect()->route('djsessions.index')->with('error', 'El pago fue cancelado.');
    }



    public function show()
    {
        return view('loader');
    }
    
    public function checkStatus(Request $request)
    {
        // Aquí puedes verificar el estado de lo que está cargando
        // Por ejemplo, el estado del pago
        
        // Simulación de lógica de verificación
        $status = $this->checkPaymentStatus($request->input('payment_id', null));
        
        return response()->json([
            'status' => $status,
            'message' => $this->getStatusMessage($status)
        ]);
    }
    
    private function checkPaymentStatus($paymentId)
    {
        // Tu lógica para verificar el estado del pago
        // Retorna: 'pending', 'processing', 'confirmed', 'failed'
        return 'processing'; // Ejemplo
    }
    
    private function getStatusMessage($status)
    {
        $messages = [
            'pending' => 'Iniciando verificación del pago...',
            'processing' => 'Confirmando tu pago...',
            'confirmed' => '¡Pago confirmado exitosamente!',
            'failed' => 'Error al procesar el pago.'
        ];
        
        return $messages[$status] ?? 'Procesando...';
    }


}
