<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->hash_id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'icon'        => $this->icon,
            'description' => $this->description,
            'is_active'   => $this->is_active,
            'sort_order'  => $this->sort_order,
            'services_count' => $this->whenCounted('services'),
            'created_at'  => $this->created_at?->toISOString(),
        ];
    }
}
