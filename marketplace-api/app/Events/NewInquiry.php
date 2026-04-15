<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewInquiry implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Chat $chat,
        public int $workerId,
        public string $serviceTitle,
    ) {}

    /**
     * Broadcast to the worker's private channel.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("user.{$this->workerId}"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'chat_id'       => $this->chat->hash_id,
            'service_title' => $this->serviceTitle,
            'from'          => $this->chat->user?->name,
        ];
    }

    public function broadcastAs(): string
    {
        return 'inquiry.new';
    }
}
