<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->hash_id,
            'user_id'       => $this->user?->hash_id,
            'plan_type'     => $this->plan_type,
            'starts_at'     => $this->starts_at?->toDateString(),
            'expires_at'    => $this->expires_at?->toDateString(),
            'is_active'     => $this->is_active,
            'is_expired'    => $this->isExpired(),
            'is_trial'      => $this->isTrial(),
            'days_remaining'=> $this->daysRemaining(),
            'amount_paid'   => $this->amount_paid,
            'admin_notes'   => $this->when($request->user() instanceof \App\Models\Admin, $this->admin_notes),
            'plan'          => new SubscriptionPlanResource($this->whenLoaded('plan')),
            'user'          => new UserResource($this->whenLoaded('user')),
            'created_at'    => $this->created_at?->toISOString(),
        ];
    }
}
