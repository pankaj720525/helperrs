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
            'admin_id'  => $this->admin_id,
            'is_admin'  => !is_null($this->admin_id),
            'message'   => $this->message,
            'image_url' => $this->image_path ? asset('storage/' . $this->image_path) : null,
            'is_read'   => $this->is_read,
            'sender'    => $this->admin_id ? [
                'name' => $this->admin?->name ?? 'Support Representative',
                'role' => 'Support Admin',
            ] : new UserResource($this->whenLoaded('sender')),
            'created_at'=> $this->created_at?->toISOString(),
        ];
    }
}
