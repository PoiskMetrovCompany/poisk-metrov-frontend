<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ManagerMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private string $userChatToken;
    public string $message;
    public string|null $managerProfilePic;
    public string $managerName;
    public string $time;

    /**
     * Create a new event instance.
     */
    public function __construct(string $chatToken, string $message, string|null $managerProfilePic, string $managerName)
    {
        $this->userChatToken = $chatToken;
        $this->message = $message;
        $this->managerProfilePic = $managerProfilePic;
        $this->managerName = $managerName;
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
            new Channel("manager-message.{$this->userChatToken}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'messaged';
    }
}
