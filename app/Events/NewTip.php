<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;



class NewTip implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tipId;
    public $djsessionId;
    public $userId;
    public $status; // 'paid', 'failed', etc.
    public $tipAmount;
    public $donorAmount;

    public function __construct($tipId, $djsessionId, $userId, $status, $tipAmount = null, $donorAmount = null)
    {
        $this->tipId = $tipId;
        $this->djsessionId = $djsessionId;
        $this->userId = $userId;
        $this->status = $status;
        $this->tipAmount = $tipAmount;
        $this->donorAmount = $donorAmount;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('user.' . $this->userId),
        ];

        if ($this->status === 'paid') {
            $channels[] = new PrivateChannel('djsession.' . $this->djsessionId);
        }

        return $channels;
    }

    public function broadcastWith()
    {
        return [
            'tip_id' => $this->tipId,
            'djsession_id' => $this->djsessionId,
            'user_id' => $this->userId,
            'status' => $this->status,
            'tip_amount' => $this->tipAmount,
            'donor_amount' => $this->donorAmount,
        ];
    }
}

