<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminAuditLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->hash_id,
            'admin_id'    => $this->admin?->hash_id,
            'action'      => $this->action,
            'target_type' => $this->target_type,
            'target_id'   => $this->target_id, // kept as-is since it references polymorphic targets
            'old_values'  => $this->old_values,
            'new_values'  => $this->new_values,
            'ip_address'  => $this->ip_address,
            'admin'       => $this->whenLoaded('admin', fn() => [
                'id'   => $this->admin->hash_id,
                'name' => $this->admin->name,
            ]),
            'created_at'  => $this->created_at?->toISOString(),
        ];
    }
}
