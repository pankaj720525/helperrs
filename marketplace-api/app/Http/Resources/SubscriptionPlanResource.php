<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->hash_id,
            'name'          => $this->name,
            'type'          => $this->type,
            'price'         => $this->price,
            'duration_days' => $this->duration_days,
            'features'      => $this->features,
            'is_active'     => $this->is_active,
            'created_at'    => $this->created_at?->toISOString(),
        ];
    }
}
