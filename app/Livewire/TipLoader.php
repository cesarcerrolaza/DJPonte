<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class TipLoader extends Component
{
    public $message = "Estamos confirmando tu pago... esto puede tardar unos segundos.";
    public $secondaryMessage = "Estamos tardando más de lo normal en confirmar tu pago. Espera unos segundos.";
    public $delay = 10; // segundos antes de cambiar el mensaje
    public $userId;
    public $tipId;
    
    public function mount($tipId, $message = null, $secondaryMessage = null, $delay = 10)
    {
        $this->tipId = $tipId;
        if ($message) {
            $this->message = $message;
        }
        
        if ($secondaryMessage) {
            $this->secondaryMessage = $secondaryMessage;
        }
        
        $this->delay = $delay;

        $this->userId = auth()->id(); // Obtener el ID del usuario autenticado
    }

    #[On('echo:user.{userId},NewTip')]
    public function handleNewTip($payload)
    {
        if ($payload['tip_id'] !== $this->tipId) {
            return; // Ignorar eventos que no son de la propina actual
        }
        if ($payload['status'] === 'paid') {
            redirect()->route('djsessions.index')->with('flash.banner', '¡Gracias por tu propina!')
                                                ->with('flash.bannerStyle', 'success');
        } elseif ($payload['status'] === 'failed') {
            redirect()->route('djsessions.index')->with('flash.banner', 'El pago fue cancelado.')
                                                ->with('flash.bannerStyle', 'danger');
        } else {
            redirect()->route('djsessions.index')->with('flash.banner', 'El pago no se pudo confirmar. Comprueba el pago.')
                                                ->with('flash.bannerStyle', 'warning');
        }
    }

    public function render()
    {
        return view('components.loader', [
        'message' => $this->message,
        'secondaryMessage' => $this->secondaryMessage,
        'delay' => $this->delay,
        ]);
    }
}