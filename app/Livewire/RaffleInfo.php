<?php

namespace App\Livewire;

use App\Models\Raffle;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class RaffleInfo extends Component
{
    public $raffle;
    public $winner = null;
    public $lastParticipant = null;
    public $participantsCount = 0;
    public $viewType;
    public $djsessionId;


    public function mount($djsessionId, $viewType = 'management')
    {
        $this->raffle = \App\Models\Raffle::where('djsession_id', $djsessionId)
            ->where('is_current', true)
            ->first();
        $this->viewType = $viewType;
        $this->djsessionId = $djsessionId;
        $this->loadRaffleInfo();
    }

    public function loadRaffleInfo()
    {
        $raffle = $this->raffle;
        $this->winner = $raffle?->winner?->name ?? null;
        $this->participantsCount = $this->raffle?->participants_count ?? 0;
    }

    public function getListeners()
    {
        return [
            "echo:djsession.{$this->djsessionId},NewRaffleParticipant" => 'newRaffleParticipant',
            "echo:djsession.{$this->djsessionId},RaffleWinner" => 'setRaffleWinner',
            "echo:djsession.{$this->djsessionId},RaffleOperation" => 'handleRaffleOperation',
        ];
    }

    public function newRaffleParticipant($payload)
    {
        if ($payload['raffle_id'] === $this->raffle->id) {
            $this->lastParticipant = $payload['participant_name'];
            $this->participantsCount++;
        }
        else {
            $this->setCurrentRaffle($payload['raffle_id']);
        }
    }

    public function setRaffleWinner($payload)
    {
        Log::info("RaffleInfo - Received RaffleWinner event: " . json_encode($payload));

        if ($payload['raffle_id'] === $this->raffle->id) {

            $winnerName = 'Desconocido';
            $winnerId = $payload['winner_id'];

            if ($payload['winner_type'] === Raffle::USER_APP) {
                $winnerName = optional(\App\Models\User::find($winnerId))->name ?? 'Usuario no encontrado';
            } elseif ($payload['winner_type'] === Raffle::USER_SOCIAL) {
                $winnerName = optional(\App\Models\SocialUser::find($winnerId))->name ?? 'Usuario no encontrado';
            }
            
            $this->winner = $winnerName;

            $winnerData = [
                'id' => $winnerId,
                'name' => $winnerName
            ];

            $this->dispatch('raffle-winner', winner: $winnerData);

        } else {
            Log::info("RaffleInfo - Ignored event for a different raffle.");
        }
    }

    public function handleRaffleOperation($payload)
    {
        Log::info("RaffleInfo - Received RaffleOperation event: " . json_encode($payload));
        switch ($payload['operation']) {
            case 'set_current':
                $this->setCurrentRaffle($payload['raffle_id']);
                break;
            case 'update':
                $this->updateRaffle($payload['raffle_id']);
                break;
            case 'delete':
                $this->deleteRaffle($payload['raffle_id']);
                break;
            case Raffle::STATUS_OPEN:
                $this->openRaffle($payload['raffle_id']);
                break;
            case Raffle::STATUS_CLOSED:
                $this->closeRaffle($payload['raffle_id']);
                break;
            case Raffle::STATUS_TERMINATED:
                $this->deleteRaffle($payload['raffle_id']);
                break;
            default:
                break;
        }
    }

    public function setCurrentRaffle($raffleId)
    {
        if (!$this->raffle || $this->raffle->id !== $raffleId) {
            $this->raffle = \App\Models\Raffle::find($raffleId);
            $this->loadRaffleInfo();
        }
    }

    public function updateRaffle($raffleId)
    {
        if ($this->raffle && $this->raffle->id === $raffleId) {
            $this->raffle->refresh();
            $this->loadRaffleInfo();
        }
    }

    public function deleteRaffle($raffleId)
    {
        if ($this->raffle && $this->raffle->id === $raffleId) {
            unset($this->raffle);
            Log::info("RaffleInfo - Deleted raffle with ID: {$raffleId}");
            $this->raffle = null;
        }
    }

    public function openRaffle($raffleId)
    {
        Log::info("RaffleInfo - Opening raffle with ID: {$raffleId}");
        if ($this->raffle && $this->raffle->id === $raffleId) {
            $this->raffle->status = 'open';
        }
        else {
            $this->setCurrentRaffle($raffleId);
        }
    }

    public function closeRaffle($raffleId)
    {
        Log::info("RaffleInfo - Closing raffle with ID: {$raffleId}");
        if ($this->raffle && $this->raffle->id === $raffleId) {
            $this->raffle->status = 'closed';
        }
        else {
            $this->setCurrentRaffle($raffleId);
        }
    }



    public function render()
    {
        if ($this->viewType === 'management') {
            return view('livewire.raffles.raffle-info-management');
        } 
        elseif ($this->viewType === 'summary') {
            return view('livewire.raffles.raffle-info-summary');
        }
        elseif ($this->viewType === 'form'){
            return view('livewire.raffles.raffle-info-form');
        }
    }
}
