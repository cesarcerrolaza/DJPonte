<?php

namespace App\Livewire;

use App\Models\Djsession;
use App\Services\DjsessionService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

use Livewire\Attributes\On;

class DjsessionCard extends Component
{


    public $djsession;
    public $location;
    public $djName;
    public $djAvatar;
    public $role;
    public $showUserOptions;
    public $isCurrentDjsession;
    public $djsessionId;
    public $actionShown = null;



    public function mount($djsession, $location, $djName, $djAvatar, $role)
    {
        $this->djsessionId = $djsession->id;
        $this->djsession = $djsession;
        $this->location = $location;
        $this->djName = $djName ?? 'DJ Anonymus';
        $this->djAvatar = $djAvatar ?? 'storage/users/default-avatar.jpg';
        $this->role = $role;
        $this->showUserOptions = $role === 'user';
        $this->isCurrentDjsession = true;
        $this->actionShown = null;
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

    public function toggleStatus()
    {
        if (!$this->djsession->active) {
            app(DjsessionService::class)->activate($this->djsession, auth()->user());
        } else {
            app(DjsessionService::class)->deactivate($this->djsession);
        }
    }

    public function newParticipantJoined()
    {
        $this->djsession->participants += 1;
    }
    public function newParticipantLeft()
    {
        $this->djsession->participants -= 1;
    }

    public function setCurrent()
    {
        if ($this->isCurrentDjsession) {
            if ($this->role === 'dj') {
                app(DjsessionService::class)->deactivate($this->djsession);
            } else {
                app(DjsessionService::class)->leave($this->djsession, auth()->user());
            }
            $this->isCurrentDjsession = false;
        } else {
            if ($this->role === 'dj') {
                app(DjsessionService::class)->activate($this->djsession, auth()->user());
            } else {
                app(DjsessionService::class)->join($this->djsession, auth()->user());
            }
            $this->isCurrentDjsession = true;
        }
    }

    #[On('echo:djsession.{djsessionId},DjsessionUpdate')]
    public function djsessionUpdate($eventData)
    {
        Log::info('Djsession update event received', ['eventData' => $eventData]);
        if (!isset($eventData['active']) && !isset($eventData['current_users'])) {
            $this->djsession = Djsession::find($eventData['djsession_id']);
            $this->location = $this->djsession->fullLocation();
            
        }
        else {
            if (isset($eventData['current_users'])) {
                $this->djsession->current_users = $eventData['current_users'];
            }
            if (isset($eventData['active'])){
                $this->djsession->active = $eventData['active'];
                $this->showUserOptions = ($this->role === 'user' && $this->djsession->active);
                $this->isCurrentDjsession = $this->djsession->active && $this->isCurrentDjsession;
            }
        }
        // Actualizar la lista de peticiones
        //$this->loadRequests($eventData['djsession_id']);
    }

    //
    public function showAction($type){
        switch ($type) {
            case 'song-request':
                // Lógica para solicitar canciones
                //$this->emit('openSongRequestModal');
                $this->actionShown = 'song-request';
                break;
            case 'tip':
                // Lógica para propinas
                $this->emit('openTipModal');
                break;
            case 'raffle':
                // Lógica para sorteos
                $this->emit('openRaffleModal');
                break;
        }
    }

    public function render()
    {
        return view('livewire.djsession-card');
    }
}
