<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->hash_id,
            'user_id'         => $this->user?->hash_id,
            'category_id'     => $this->category?->hash_id,
            'title'           => $this->title,
            'description'     => $this->description,
            'image'           => $this->image ? asset('storage/' . $this->image) : null,
            'price_min'       => $this->price_min,
            'price_max'       => $this->price_max,
            'status'          => $this->status,
            'rejection_reason'=> $this->when($this->status === 'rejected', $this->rejection_reason),
            'category'        => new CategoryResource($this->whenLoaded('category')),
            'user'            => new UserResource($this->whenLoaded('user')),
            'worker_profile'  => new WorkerProfileResource($this->whenLoaded('user.workerProfile')),
            'reviews_avg'     => round($this->reviews_avg_rating ?? 0, 1),
            'reviews_count'   => $this->reviews_count ?? 0,
            'approved_at'     => $this->approved_at?->toISOString(),
            'created_at'      => $this->created_at?->toISOString(),
        ];
    }
}
