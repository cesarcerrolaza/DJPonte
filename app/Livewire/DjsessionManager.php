<?php

namespace App\Livewire;

use App\Models\Djsession;
use App\Models\SongRequest;
use Livewire\Component;

class DjSessionManager extends Component
{

    //Sesion
    public $djsession;

    public $address = "Direccion"; //TODO


    //Own
    public $exitUrl;
    public $activeTab = 'canciones';


    protected $listeners = [
        'updateParticipants' => 'updateParticipantsCount'
    ];

    public function mount($djsessionId)
    {

        $this->djsession = Djsession::find($djsessionId);
    }

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function updateParticipantsCount($participants)
    {
        $this->djsession->participants = $participants;
    }


    public function newParticipantJoined()
    {
        $this->djsession->participants+=1;
    }

    public function newParticipantLeft()
    {
        $this->djsession->participants-=1;
    }
 

    public function render()
    {
        return view('livewire.djsession-manager');
    }
}
