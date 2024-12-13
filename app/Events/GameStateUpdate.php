<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameStateUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $gameState;
    private $roomId;

    public function __construct($gameState, $roomId)
    {
        $this->gameState = $gameState;
        $this->roomId = $roomId;
    }

    public function broadcastOn()
    {
        return new Channel('game.' . $this->roomId);
    }
}
