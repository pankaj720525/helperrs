<?php

namespace App\Models\Concerns;

use App\Services\HashIdService;

trait HasHashId
{
    /**
     * Get the encrypted hash ID for this model.
     */
    public function getHashIdAttribute(): string
    {
        return app(HashIdService::class)->encode($this);
    }

    /**
     * Find a model by its encrypted hash ID.
     */
    public static function findByHashId(string $hash): ?static
    {
        $id = app(HashIdService::class)->decode($hash, (new static)->getTable());
        return $id ? static::find($id) : null;
    }

    /**
     * Find a model by its encrypted hash ID or throw 404.
     */
    public static function findByHashIdOrFail(string $hash): static
    {
        $model = static::findByHashId($hash);
        if (!$model) {
            abort(404, 'Resource not found.');
        }
        return $model;
    }

    /**
     * Resolve a route binding using hash ID.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if ($field) {
            return parent::resolveRouteBinding($value, $field);
        }

        return static::findByHashIdOrFail($value);
    }
}
