<?php

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request for Admin routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = $request->user();

        if (! $admin || ! ($admin instanceof \App\Models\AdminUser) || ! $admin->is_active) {
            return ApiResponse::unauthorized('Unauthorized admin access.');
        }

        return $next($request);
    }
}
