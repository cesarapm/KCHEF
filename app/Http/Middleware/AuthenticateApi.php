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
        // Log para debugging
        $sessionId = $request->session()->getId();
        $isAuth = auth()->check();
        $user = auth()->user();
        
        \Log::debug("AuthenticateApi - SessionID: {$sessionId}, Auth: {$isAuth}, User: " . ($user?->email ?? 'null'));
        
        if (!auth()->check()) {
            \Log::warning("AuthenticateApi - No auth for request to: " . $request->path());
            return response()->json([
                'message' => 'No autorizado - Por favor inicia sesión',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
