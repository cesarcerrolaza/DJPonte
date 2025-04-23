<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DjsessionUpdate implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $djsession_id;
    public $current_users;
    public $active;

    /**
     * Create a new event instance.
     */
    public function __construct($session_id, $data)
    {
        $this->djsession_id = $session_id;
        if (isset($data['current_users'])) {
            $this->current_users = $data['current_users'];
        }
        if (isset($data['active'])) {
            $this->active = $data['active'];
        }
    }

    public function broadcastOn()
    {
        return new Channel('djsession.' . $this->djsession_id); // Canal de Livewire
    }

    public function broadcastWith()
    {
        return [
            'djsession_id' => $this->djsession_id,
            'current_users' => $this->current_users,
            'active' => $this->active
        ];
    }
}
