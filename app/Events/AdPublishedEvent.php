<?php

namespace App\Events;

use App\Models\Adv;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdPublishedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ad;
    public $publisher;

    /**
     * Create a new event instance.
     */
    public function __construct(Adv $ad, User $publisher)
    {
        $this->ad = $ad;
        $this->publisher = $publisher;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
