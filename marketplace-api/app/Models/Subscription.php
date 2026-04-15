<?php

namespace App\Models;

use App\Models\Concerns\HasHashId;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasHashId;

    protected $fillable = [
        'user_id',
        'plan_id',
        'plan_type',
        'starts_at',
        'expires_at',
        'is_active',
        'amount_paid',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'expires_at' => 'date',
            'is_active' => 'boolean',
            'amount_paid' => 'decimal:2',
        ];
    }

    // ─── Relationships ────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    // ─── Scopes ───────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('expires_at', '>=', Carbon::today());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', Carbon::today());
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function isExpired(): bool
    {
        return $this->expires_at->lt(Carbon::today());
    }

    public function isTrial(): bool
    {
        return $this->plan_type === 'trial';
    }

    public function daysRemaining(): int
    {
        return max(0, Carbon::today()->diffInDays($this->expires_at, false));
    }
}
