<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthenticateApi
{
    /**
     * Handle an incoming request - return JSON on auth failure for APIs
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return response()->json([
                'message' => 'No autorizado - Por favor inicia sesión',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
