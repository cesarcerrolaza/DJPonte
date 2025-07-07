<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Raffle;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class RaffleManagerForm extends Component
{
    use WithFileUploads;

    public ?Raffle $raffle = null;
    public $raffleId = null;
    public $djsessionId;
    public string $prize_name = '';
    public int $prize_quantity = 1;
    public $prize_image_url;
    public $prize_image;
    public string $description = '';

    protected function rules()
    {
        return [
            'prize_name' => 'required|string|max:255',
            'prize_quantity' => 'required|integer|min:1',
            'prize_image' => 'nullable|image|max:2048', // 2MB
            'description' => 'nullable|string|max:255',
        ];
    }

    public function mount($raffleId = null, $djsessionId = null)
    {
        $this->djsessionId = $djsessionId;
        $this->loadRaffleData($raffleId, $djsessionId);
    }

    #[\Livewire\Attributes\On('loadRaffleForm')] 
    public function loadRaffleData($raffleId = null)
    {
        $this->raffleId = $raffleId;
        $this->raffle = Raffle::find($raffleId);
        if ($this->raffle) {
            $this->prize_name = $this->raffle->prize_name;
            $this->prize_quantity = $this->raffle->prize_quantity;
            $this->description = $this->raffle->description;
            $this->prize_image = $this->raffle->prize_image;
        }
        $this->prize_image_url = $this->raffle->prize_image_url ?? asset('storage/raffles/default.png');
    
    }

    #[\Livewire\Attributes\On('resetRaffleForm')] 
    public function resetRaffleData($raffleId = null)
    {
        $this->raffleId = null;
        if ($this->raffle) {
            $this->prize_name = '';
            $this->prize_quantity = 1;
            $this->description = '';
        }
    
    }

    public function save()
    {
        $this->validate();

        $data = [
            'prize_name' => $this->prize_name,
            'prize_quantity' => $this->prize_quantity,
            'description' => $this->description,
        ];

        if ($this->prize_image) {
            $data['prize_image'] = $this->prize_image->store('raffles', 'public');
        }

        if ($this->raffle) {
            $this->raffle->update($data);
            if ($this->raffle->isCurrent()) {
                broadcast(new \App\Events\RaffleOperation($this->djsessionId, $this->raffleId, 'update'));
            }
        } else {
            $data['dj_id'] = Auth::id();
            $data['djsession_id'] = $this->djsessionId;
            $newRaffle = Raffle::create($data);
            $this->dispatch('raffle-created', [
                'raffle_id' => $newRaffle->id
            ]);
        }

        // Opcional: emitir evento para cerrar modal desde Alpine
        $this->dispatch('close-raffle-form');

        session()->flash('status', 'Sorteo guardado correctamente.');
    }

    public function render()
    {
        return view('livewire.raffles.raffle-manager-form');
    }
}
