<?php

namespace App\Http\Middleware;

use App\Services\HashIdService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DecryptHashIds
{
    public function __construct(
        protected HashIdService $hashIdService
    ) {}

    /**
     * Decrypt hash IDs from route parameters and request body.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Decrypt route parameters (e.g., {id}, {service}, {user}, {chat})
        foreach ($request->route()->parameters() as $key => $value) {
            if (is_string($value) && !is_numeric($value)) {
                $decoded = $this->hashIdService->decodeGeneric($value);
                if ($decoded !== null) {
                    $request->route()->setParameter($key, $decoded);
                }
            }
        }

        // Decrypt request body fields ending in _id
        $input = $request->all();
        $modified = false;

        foreach ($input as $key => $value) {
            if (str_ends_with($key, '_id') && is_string($value) && !is_numeric($value)) {
                $decoded = $this->hashIdService->decodeGeneric($value);
                if ($decoded !== null) {
                    $input[$key] = $decoded;
                    $modified = true;
                }
            }
        }

        if ($modified) {
            $request->merge($input);
        }

        return $next($request);
    }
}
