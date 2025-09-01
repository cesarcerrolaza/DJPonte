<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Models\SongRequest;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SongRequestStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $songRequest;
    public $newStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(SongRequest $songRequest)
    {
        $this->songRequest = $songRequest;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('song-requests'); // Canal de Livewire
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->songRequest->id,
            'status' => $this->songRequest->status
        ];
    }
}
