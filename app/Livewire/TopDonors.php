<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use App\Models\Donor;
use Illuminate\Support\Facades\Log;

class TopDonors extends Component
{
    public $topDonors = [];
    public $djsessionId = null;
    public $viewType = null;

    public function mount($djsessionId, $viewType)
    {
        $this->viewType = $viewType;
        $this->djsessionId = $djsessionId;
        $this->loadTopDonors();
    }

    public function loadTopDonors()
    {
        $this->topDonors = Donor::where('djsession_id', $this->djsessionId)
            ->orderByDesc('amount')
            ->take(3)
            ->get()
            ->map(function ($donor) {
                return [
                    'user_id' => $donor->user_id,
                    'user' => $donor->user->name,
                    'amount' => $donor->amount,
                ];
            })
            ->toArray(); // Convertir explícitamente a array
    }

    public function updateDonorsRanking($payload)
    {
        // Elimina si ya estaba y añade el nuevo donante
        $this->topDonors = collect($this->topDonors)
            ->reject(fn($donor) => $donor['user_id'] === $payload['user_id'])
            ->push([
                'user_id' => $payload['user_id'],
                'user' => User::find($payload['user_id'])->name,
                'amount' => $payload['donor_amount'],
            ])
            ->sortByDesc('amount')
            ->take(3)
            ->values()
            ->toArray(); // Convertir a array
    }

    public function getListeners()
    {
        return [
            "echo-private:djsession.{$this->djsessionId},NewTip" => 'handleNewTip',
        ];
    }

    public function handleNewTip($payload)
    {
        if ($payload['status'] === 'paid') {
            $this->updateDonorsRanking($payload);
        }
    }
    
    public function render()
    {
        if ($this->viewType === 'management') {
            return view('livewire.donors.donor-management-view');
        } 
        elseif ($this->viewType === 'summary') {
            return view('livewire.donors.donor-summary-view');
        }
        elseif ($this->viewType === 'form'){
            return view('livewire.donors.donor-form-view');
        }
    }
}