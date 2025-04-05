<?php

namespace App\Events;

use App\Models\SongRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewSongRequest implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $songRequest;

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
            'djsession_id' => $this->songRequest->djsession_id,
            'title' => optional($this->songRequest->song)->title ?? $this->songRequest->custom_title,
            'artist' => optional($this->songRequest->song)->artist ?? $this->songRequest->custom_artist,
            'score' => $this->songRequest->score
        ];
    }

}
