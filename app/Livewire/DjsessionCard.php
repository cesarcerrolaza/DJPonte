<?php

namespace App\Livewire;

use Livewire\Component;

class DjsessionCard extends Component
{


    public $djsession;
    public $location;
    public $djName;
    public $djAvatar;
    public $role;



    public function mount($djsession, $location, $djName, $djAvatar, $role)
    {
        $this->djsession = $djsession;
        $this->location = $location;
        $this->djName = $djName ?? 'DJ Anonymus';
        $this->djAvatar = $djAvatar ?? 'storage/users/default-avatar.jpg';
        $this->role = $role;
        /*
        $this->title = 'Sesión Gózalo';
        $this->sessionCode = '26543';
        $this->address = 'Paseo Almte. Pascual Pery, 25, 11004 Cádiz';
        $this->venueImage = 'storage/djsessions/momart.jpg';
        $this->venueName = 'Momart';
        $this->djName = 'DJ Avatar';
        $this->djAvatar = 'storage/users/dj-avatar.jpg';
        $this->participants = 89;
        $this->exitUrl = route('djsession.exit');
        */
    }

    public function render()
    {
        return view('livewire.djsession-card');
    }
}
