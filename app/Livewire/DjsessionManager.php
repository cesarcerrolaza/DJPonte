<?php

namespace App\Livewire;

use App\Models\Djsession;
use App\Services\DjsessionService;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class DjSessionManager extends Component
{

    //Sesion
    public $djsession;
    public $djsessionId;
    public $location;
    public $dj;
    public $confirmingSessionDeletion = false;


    //Own
    public $exitUrl;
    public $activeTab = 'canciones';


    public function mount(Djsession $djsession)
    {
        $this->dj = $djsession->dj;
        $this->djsession = $djsession;
        $this->djsessionId = $djsession->id;
        $this->location = $djsession->fullLocation();
    }

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function toggleStatus()
    {
        if (!$this->djsession->active) {
            app(DjsessionService::class)->activate($this->djsession, $this->dj);
        } else {
            app(DjsessionService::class)->deactivate($this->djsession);
        }
        $this->djsession->refresh();
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
            }
        }
        // Actualizar la lista de peticiones
        //$this->loadRequests($eventData['djsession_id']);
    }
    
    public function confirmDelete()
    {
        $this->confirmingSessionDeletion = true;
    }


    public function render()
    {
        return view('livewire.djsession-manager');
    }
}
