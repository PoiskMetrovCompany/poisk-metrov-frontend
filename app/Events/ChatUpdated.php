<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ChatUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $chatToken;
    public array $chatData;
    public bool $isNew;

    /**
     * Create a new event instance.
     */
    public function __construct(string $chatToken, array $chatData, bool $isNew)
    {
        $this->chatToken = $chatToken;
        $this->chatData = $chatData;
        $this->chatData['chatToken'] = $chatToken;
        $this->isNew = $isNew;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("chat-updated"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'updated';
    }
}
