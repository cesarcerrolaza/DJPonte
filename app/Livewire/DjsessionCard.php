<?php

namespace App\Livewire;

use App\Models\Djsession;
use App\Services\DjsessionService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

use Illuminate\Support\Facades\Auth;

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
    public $raffleInfoKey;



    public function mount($djsession, $location, $djName, $djAvatar, $role)
    {
        $this->djsessionId = $djsession->id;
        $this->djsession = $djsession;
        $this->location = $location;
        $this->djName = $djName ?? 'DJ Anonymus';
        $this->djAvatar = $djAvatar ?? 'storage/users/default.png';
        $this->role = $role;
        $this->showUserOptions = $role === 'user';
        $this->isCurrentDjsession = true;
        $this->actionShown = null;
        $this->refreshRaffle();
    }

    public function toggleStatus()
    {
        if (!$this->djsession->active) {
            app(DjsessionService::class)->activate($this->djsession, Auth::user());
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
                app(DjsessionService::class)->leave($this->djsession, Auth::user());
            }
            $this->isCurrentDjsession = false;
        } else {
            if ($this->role === 'dj') {
                app(DjsessionService::class)->activate($this->djsession, Auth::user());
            } else {
                app(DjsessionService::class)->join($this->djsession, Auth::user());
            }
            $this->isCurrentDjsession = true;
        }
    }

    public function getListeners()
    {
        return [
            "echo:djsession.{$this->djsessionId},DjsessionUpdate" => 'djsessionUpdate',
            "echo:djsession.{$this->djsessionId},CurrentRaffleDeleted" => 'refreshRaffle',
            "echo:djsession.{$this->djsessionId},DjsessionDeleted" => 'djsessionDeleted',
        ];
    }

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
    }

    public function djsessionDeleted()
    {
        $this->dispatch('session-deleted-reload');
    }

    //
    public function showAction($type){
        switch ($type) {
            case 'song-request':
                $this->actionShown = 'song-request';
                break;
            case 'tip':
                $this->actionShown = 'tip';
                break;
            case 'raffle':
                $this->emit('openRaffleModal');
                break;
        }
    }

    public function refreshRaffle()
    {
        $this->raffleInfoKey = 'raffle-djsesion-card-' . uniqid();
    }

    public function render()
    {
        return view('livewire.djsession-card');
    }
}
