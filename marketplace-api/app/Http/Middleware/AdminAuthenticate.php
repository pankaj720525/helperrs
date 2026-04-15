<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    /**
     * Ensure the request is authenticated as an admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('sanctum');

        if (!$user || !($user instanceof \App\Models\Admin)) {
            return response()->json([
                'message' => 'Unauthenticated. Admin access required.',
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Your admin account has been deactivated.',
            ], 403);
        }

        return $next($request);
    }
}
