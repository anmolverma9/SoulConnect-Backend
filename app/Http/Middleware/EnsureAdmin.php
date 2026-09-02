<?php

namespace App\Http\Middleware;

use App\Models\AdminUser;
use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request for Admin routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = $request->user();

        // Fallback for LiteSpeed/Apache stripping Authorization header
        if (! $admin) {
            $tokenString = $request->bearerToken() 
                ?: $request->header('X-Admin-Token') 
                ?: $request->header('Authorization')
                ?: $request->input('api_token');

            if ($tokenString) {
                if (str_starts_with($tokenString, 'Bearer ')) {
                    $tokenString = substr($tokenString, 7);
                }

                $accessToken = PersonalAccessToken::findToken($tokenString);
                if ($accessToken && $accessToken->tokenable instanceof AdminUser) {
                    $admin = $accessToken->tokenable;
                    $request->setUserResolver(fn () => $admin);
                    auth('sanctum')->setUser($admin);
                }
            }
        }

        if (! $admin || ! ($admin instanceof AdminUser) || ! $admin->is_active) {
            return ApiResponse::unauthorized('Unauthenticated admin session.');
        }

        return $next($request);
    }
}
