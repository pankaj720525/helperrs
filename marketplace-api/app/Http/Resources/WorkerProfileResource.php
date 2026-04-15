<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->hash_id,
            'user_id'       => $this->user?->hash_id,
            'address'       => $this->address,
            'bio'           => $this->bio,
            'phone_public'  => $this->when($request->user(), $this->phone_public),
            'email_public'  => $this->when($request->user(), $this->email_public),
            'specialties'   => $this->specialties,
            'is_available'  => $this->is_available,
            'user'          => new UserResource($this->whenLoaded('user')),
            'created_at'    => $this->created_at?->toISOString(),
        ];
    }
}
