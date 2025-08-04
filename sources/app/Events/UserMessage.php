<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $userName;
    public string $userChatToken;
    public string $message;
    public string $time;

    /**
     * Create a new event instance.
     */
    public function __construct(string $userName, string $chatToken, string $message)
    {
        $this->userName = $userName;
        $this->userChatToken = $chatToken;
        $this->message = $message;
        $this->time = date_format(new \DateTime(), "Y/m/d H:i:s");
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("user-message.{$this->userChatToken}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'messaged';
    }
}
