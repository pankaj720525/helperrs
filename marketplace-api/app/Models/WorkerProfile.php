<?php

namespace App\Models;

use App\Models\Concerns\HasHashId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class WorkerProfile extends Model
{
    use HasHashId;

    protected $fillable = [
        'user_id',
        'address',
        'bio',
        'phone_public',
        'email_public',
        'specialties',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'specialties' => 'array',
            'is_available' => 'boolean',
        ];
    }

    // ─── Relationships ────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Location Helpers ─────────────────────────────────────

    /**
     * Set the location using latitude and longitude.
     */
    public function setLocation(float $latitude, float $longitude): void
    {
        DB::statement(
            "UPDATE worker_profiles SET location = ST_GeomFromText('POINT(? ?)') WHERE id = ?",
            [$longitude, $latitude, $this->id]
        );
    }

    /**
     * Get latitude from POINT column.
     */
    public function getLatitudeAttribute(): ?float
    {
        if (!$this->location) {
            return null;
        }

        $result = DB::selectOne(
            'SELECT ST_Y(location) as lat FROM worker_profiles WHERE id = ?',
            [$this->id]
        );

        return $result?->lat;
    }

    /**
     * Get longitude from POINT column.
     */
    public function getLongitudeAttribute(): ?float
    {
        if (!$this->location) {
            return null;
        }

        $result = DB::selectOne(
            'SELECT ST_X(location) as lng FROM worker_profiles WHERE id = ?',
            [$this->id]
        );

        return $result?->lng;
    }
}
