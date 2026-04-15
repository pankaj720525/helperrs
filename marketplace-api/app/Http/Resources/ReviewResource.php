<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->hash_id,
            'user_id'      => $this->user?->hash_id,
            'service_id'   => $this->service?->hash_id,
            'worker_id'    => $this->worker?->hash_id,
            'rating'       => $this->rating,
            'comment'      => $this->comment,
            'is_moderated' => $this->is_moderated,
            'user'         => new UserResource($this->whenLoaded('user')),
            'service'      => new ServiceResource($this->whenLoaded('service')),
            'created_at'   => $this->created_at?->toISOString(),
        ];
    }
}
