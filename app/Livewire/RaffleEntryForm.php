<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class RaffleEntryForm extends Component
{
    use WithFileUploads;
    public $djsession;
    public $djsessionId;
    public $raffle;
    public $canParticipate = false;

    public function mount($djsession)
    {
        $this->djsessionId = $djsession->id;
        $this->raffle = $this->djsession->currentRaffle;
        if($this->raffle?->status === 'open') {
            $this->canParticipate = !$this->raffle->hasAppParticipant(Auth::id());
        }
    }

    public function getListeners()
    {
        return [
            "echo:djsession.{$this->djsessionId},RaffleOperation" => 'handleRaffleOperation',
        ];
    }

    public function handleRaffleOperation($payload)
    {
        Log::info("RaffleEntryForm - Received RaffleOperation event: " . json_encode($payload));
        switch ($payload['operation']) {
            case 'set_current':
                $this->setCurrentRaffle($payload['raffle_id']);
                break;
            case 'delete':
                $this->deleteRaffle($payload['raffle_id']);
                break;
            case 'open':
                $this->openRaffle($payload['raffle_id']);
                break;    
            case 'closed':
                $this->closeRaffle($payload['raffle_id']);
                break;
            default:
                break;
        }
    }

    public function setCurrentRaffle($raffleId)
    {
        if ($this->raffle?->id !== $raffleId) {
            $this->raffle = \App\Models\Raffle::find($raffleId);
            if ($this->raffle?->status === 'open') {
                $this->canParticipate = !$this->raffle->hasAppParticipant(Auth::id());
            } else {
                $this->canParticipate = false;
            }
        }
    }

    public function deleteRaffle($raffleId)
    {
        if ($this->raffle?->id === $raffleId) {
            $this->raffle = null;
            $this->canParticipate = false;
        }
    }

    public function openRaffle($raffleId)
    {
        if ($this->raffle?->id === $raffleId) {
            $this->raffle->status = 'open';
        }
        else {
            $this->raffle = \App\Models\Raffle::find($raffleId);
        }
        $this->canParticipate = !$this->raffle->hasAppParticipant(Auth::id());
    }

    public function closeRaffle($raffleId)
    {
        Log::info("RaffleEntryForm - Closing raffle with ID: {$raffleId}");
        if ($this->raffle?->id === $raffleId) {
            $this->raffle->status = 'closed';
        }
        else {
            $this->raffle = \App\Models\Raffle::find($raffleId);
        }
        $this->canParticipate = false;
    }

    public function participate()
    {
        if ($this->raffle && $this->raffle->status === 'open') {
            $this->raffle->participateApp(Auth::id());
            session()->flash('success', 'You have successfully entered the raffle!');
            $this->canParticipate = false;
        } else {
            session()->flash('error', 'The raffle is not open for entries.');
        }
    }

    public function render()
    {
        return view('livewire.raffles.raffle-entry-form');
    }
}
