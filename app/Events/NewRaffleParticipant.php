<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewRaffleParticipant implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $djsessionId;
    public $raffleId;
    public $participantName;

    public function __construct($djsessionId, $raffleId, $participantName)
    {
        $this->djsessionId = $djsessionId;
        $this->raffleId = $raffleId;
        $this->participantName = $participantName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new Channel('djsession.' . $this->djsessionId),
        ];

        return $channels;
    }

    public function broadcastWith()
    {
        return [
            'raffle_id' => $this->raffleId,
            'participant_name' => $this->participantName
        ];
    }
}
