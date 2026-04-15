<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->hash_id,
            'chat_id'   => $this->chat?->hash_id,
            'sender_id' => $this->sender?->hash_id,
            'message'   => $this->message,
            'is_read'   => $this->is_read,
            'sender'    => new UserResource($this->whenLoaded('sender')),
            'created_at'=> $this->created_at?->toISOString(),
        ];
    }
}
