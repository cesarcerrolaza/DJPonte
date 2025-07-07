<?php

namespace App\Livewire;

use \Illuminate\Support\Facades\Log;
use Livewire\Component;

class RafflesManagement extends Component
{
    public $djsessionId;

    public $raffles;
    public $currentRaffle = null;

    public $formRaffleId = null;

    public $confirmingRaffleAction;
    public $raffleActionToConfirm;

    public $currentRaffleAction;

    public $isRaffleFormVisible = false;

    public function mount($djsessionId)
    {
        $this->djsessionId = $djsessionId;
        $this->confirmingRaffleAction = false;
        $this->loadRaffles();
        $this->resetRaffleActionToConfirm();
    }

    public function loadRaffles()
    {
        $this->raffles = \App\Models\Raffle::where('djsession_id', $this->djsessionId)
            ->orderBy('created_at', 'desc')
            ->get();

        $this->currentRaffle = $this->raffles->firstWhere('is_current', true);

        if ($this->currentRaffle) {
            $this->raffles = $this->raffles->reject(function ($r) {
                return $r->id === $this->currentRaffle->id;
            });
        }
    }

    //------------------CONFIRM-ACTIONS------------------//
    public function editRaffle($raffleId)
    {
        $this->dispatch('loadRaffleForm', raffleId: $raffleId);
        $this->isRaffleFormVisible = true;
    }

    public function closeRaffleForm()
    {
        $this->isRaffleFormVisible = false;
        $this->dispatch('resetRaffleForm');
    }

    #[\Livewire\Attributes\On('resetRaffleActionToConfirm')]
    public function resetRaffleActionToConfirm()
    {
        $this->confirmingRaffleAction = false;
        $this->raffleActionToConfirm = [
            'title' => '',
            'description' => '',
            'method' => '',
            'action' => null,
            'raffle_id' => null,
        ];
    }

    #[\Livewire\Attributes\On('executeRaffleAction')]
    public function executeRaffleAction()
    {
        if ($this->raffleActionToConfirm['method'] && method_exists($this, $this->raffleActionToConfirm['method'])) {
            $this->{$this->raffleActionToConfirm['method']}($this->raffleActionToConfirm['raffle_id']);
        }    
        $this->setCurrentRaffle($this->raffleActionToConfirm['raffle_id']);
        $this->resetRaffleActionToConfirm();
    }

    public function confirmSetCurrentRaffle($raffleId)
    {
        if ($this->currentRaffle) {
            if($this->currentRaffle->id !== $raffleId){
                $this->raffleActionToConfirm = [
                    'title' => 'Establecer sorteo como actual',
                    'description' => '¿Estás seguro de que quieres establecer este sorteo como el actual para la sesión?
                     El sorteo actual se cerrará y se mostrará este a los usuarios de la djsession.',
                    'method' => 'setCurrentRaffle',
                    'action' => 'Establecer como actual',
                    'raffle_id' => $raffleId,
                ];
                $this->confirmingRaffleAction = true;
            }
        }
        else {
            $this->setCurrentRaffle($raffleId);
        }
    }

    public function setCurrentRaffle($raffleId = null)
    {
        if (!$raffleId) {
            $raffleId = $this->raffleActionToConfirm['raffle_id'];
        }
        $raffle = $this->raffles->firstWhere('id', $raffleId);
        if ($raffle) {;
            $raffle->setCurrent(true);
            if ($this->currentRaffle) $this->raffles->prepend($this->currentRaffle);
            $this->currentRaffle = $raffle;
            $this->raffles = $this->raffles->reject(function ($r) use ($raffle) {
                return $r->id === $raffle->id;
            });
        }
    }

    public function confirmDeleteRaffle($raffleId)
    {
        $this->raffleActionToConfirm = [
                    'title' => 'Eliminar sorteo',
                    'description' => '¿Estás seguro de que quieres borrar el sorteo? Esta acción no se puede deshacer.',
                    'method' => 'deleteRaffle',
                    'action' => 'Eliminar',
                    'raffle_id' => $raffleId,
                ];
        $this->confirmingRaffleAction = true;
    }

    public function deleteRaffle($raffleId)
    {
        if ($this->currentRaffle?->id === $raffleId) {
            $this->currentRaffle->delete();
            $this->currentRaffle = null;
            broadcast(new \App\Events\CurrentRaffleDeleted($this->djsessionId));
        }
        else {
            $raffle = $this->raffles->firstWhere('id', $raffleId);
            if ($raffle) {
                $raffle->delete();
                $this->raffles = $this->raffles->reject(function ($r) use ($raffleId) {
                    return $r->id === $raffleId;
                });
            }
        }
    }

    //------------------METODOS-DE-ESTADO------------------//

    public function performRaffleAction($action)
    {
        switch ($action) {
            case 'open':
                $this->openRaffle();
                break;
            case 'close':
                $this->closeRaffle();
                break;
            case 'draw':
                $this->drawRaffle();
                break;
            case 'terminate':
                $this->terminateRaffle($this->currentRaffle->id);
                break;
            default:
                // Acción no reconocida
                break;
        }
        $this->currentRaffleAction = null;

    }

    public function openRaffle()
    {
        if ($this->currentRaffle) {
            $this->currentRaffle->open();
        }
    }

    public function drawRaffle()
    {
        if ($this->currentRaffle) {
            $this->currentRaffle->draw();
        }
    }

    public function closeRaffle()
    {
        if ($this->currentRaffle) {
            $this->currentRaffle->close();
        }
    }

    public function terminateRaffle($raffleId)
    {
        if ($this->currentRaffle && $this->currentRaffle->id === $raffleId) {
            $this->currentRaffle->terminate();
            $this->raffles->prepend($this->currentRaffle);
            $this->currentRaffle = null;
        }
        else {
            $raffle = $this->raffles->firstWhere('id', $raffleId);
            if (!$raffle) {
                return;
            }
            $raffle->terminate();
        }
    }


    //------------------LISTENER-METHODS------------------//
    public function getListeners()
    {
        return [
            // Echo broadcasts
            "echo:djsession.{$this->djsessionId},RaffleParticipant" => 'updateParticipants',
            // Local dispatches
            'raffle-updated' => 'handleUpdateRaffle',
            'raffle-created' => 'handleNewRaffle',
        ];
    }

    public function handleNewRaffle($payload)
    {
        $raffle = \App\Models\Raffle::find($payload["raffle_id"]);
        if ($raffle) {
            $this->raffles = $this->raffles->prepend($raffle);
        }
    }

    public function render()
    {
        return view('livewire.raffles.raffles-management');
    }
}
