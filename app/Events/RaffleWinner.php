<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RaffleWinner implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $djsessionId;
    public $raffleId;
    public $winnerId;
    public $winnerType; // 'App\Models\User' or 'App\Models\SocialUser'

    /**
     * Create a new event instance.
     */
    public function __construct($djsessionId, $raffleId, $winnerId, $winnerType)
    {
        $this->djsessionId = $djsessionId;
        $this->raffleId = $raffleId;
        $this->winnerId = $winnerId;
        $this->winnerType = $winnerType;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('djsession.' . $this->djsessionId),
        ];
    }

    public function broadcastWith()
    {
        return [
            #'raffle_id' => $this->raffleId,
            'raffle_id' => $this->raffleId,
            'winner_id' => $this->winnerId,
            'winner_type' => $this->winnerType,
        ];
    }
}
