<?php

namespace App\Services;

use Vinkla\Hashids\Facades\Hashids;

class HashIdService
{
    /**
     * Encode a model's ID using its table-specific connection.
     */
    public function encode(object $model): string
    {
        $connection = $this->getConnection($model->getTable());
        return Hashids::connection($connection)->encode($model->getKey());
    }

    /**
     * Encode a raw ID for a specific table.
     */
    public function encodeForTable(int $id, string $table): string
    {
        $connection = $this->getConnection($table);
        return Hashids::connection($connection)->encode($id);
    }

    /**
     * Decode a hash string for a specific table.
     */
    public function decode(string $hash, string $table): ?int
    {
        try {
            $connection = $this->getConnection($table);
            $decoded = Hashids::connection($connection)->decode($hash);
            return !empty($decoded) ? $decoded[0] : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Decode a hash string without knowing the table (tries all connections).
     * Use sparingly — prefer decode() with a known table.
     */
    public function decodeGeneric(string $hash): ?int
    {
        $connections = array_keys(config('hashids.connections', []));

        foreach ($connections as $connection) {
            try {
                $decoded = Hashids::connection($connection)->decode($hash);
                if (!empty($decoded)) {
                    return $decoded[0];
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return null;
    }

    /**
     * Get the hashids connection name for a table.
     */
    protected function getConnection(string $table): string
    {
        $connections = array_keys(config('hashids.connections', []));
        return in_array($table, $connections) ? $table : 'main';
    }
}
