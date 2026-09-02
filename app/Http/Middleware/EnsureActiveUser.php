<?php

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveUser
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return ApiResponse::unauthorized('Unauthenticated.');
        }

        if ($user->isBanned()) {
            return ApiResponse::forbidden('Your account has been suspended permanently due to policy violations.');
        }

        if ($user->isSuspended()) {
            return ApiResponse::forbidden('Your account is temporarily suspended.');
        }

        if ($user->isDeleted()) {
            return ApiResponse::unauthorized('Account no longer exists.');
        }

        // Update user activity timestamp throttled
        if (! $user->last_active_at || $user->last_active_at->diffInMinutes(now()) >= 5) {
            $user->updateQuietly(['last_active_at' => now()]);
        }

        return $next($request);
    }
}
