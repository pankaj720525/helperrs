<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currentUserId = $request->user()?->id;

        return [
            'id'          => $this->hash_id,
            'user_id'     => $this->user?->hash_id,
            'worker_id'   => $this->worker?->hash_id,
            'service_id'  => $this->service?->hash_id,
            'status'      => $this->status,
            'unread_count'=> $currentUserId ? $this->unreadCountFor($currentUserId) : 0,
            'user'        => new UserResource($this->whenLoaded('user')),
            'worker'      => new UserResource($this->whenLoaded('worker')),
            'service'     => new ServiceResource($this->whenLoaded('service')),
            'latest_message' => new ChatMessageResource($this->whenLoaded('latestMessage')),
            'created_at'  => $this->created_at?->toISOString(),
            'updated_at'  => $this->updated_at?->toISOString(),
        ];
    }
}
