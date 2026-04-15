<?php

namespace App\Models;

use App\Models\Concerns\HasHashId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasHashId;

    protected $fillable = [
        'user_id',
        'service_id',
        'worker_id',
        'rating',
        'comment',
        'is_moderated',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_moderated' => 'boolean',
        ];
    }

    // ─── Relationships ────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    // ─── Scopes ───────────────────────────────────────────────

    public function scopeModerated($query)
    {
        return $query->where('is_moderated', true);
    }

    public function scopeUnmoderated($query)
    {
        return $query->where('is_moderated', false);
    }
}
