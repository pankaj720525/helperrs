<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->hash_id,
            'name'                 => $this->name,
            'email'                => $this->email,
            'phone'                => $this->phone,
            'avatar'               => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'role'                 => $this->role,
            'is_active'            => $this->is_active,
            'notification_preferences' => $this->notification_preferences ?? [
                'email' => true,
                'push'  => true,
                'audio' => true,
            ],
            'worker_profile'       => new WorkerProfileResource($this->whenLoaded('workerProfile')),
            'created_at'           => $this->created_at?->toISOString(),
        ];
    }
}
