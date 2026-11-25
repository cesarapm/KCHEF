<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSessionIsValid
{
    /**
     * Ensure session persists across page reloads and requests
     * This middleware regenerates the session for authenticated users,
     * preventing session loss on page refresh.
     */
    public function handle(Request $request, Closure $next)
    {
        // Log para debugging
        $sessionId = $request->session()->getId();
        $isAuthenticated = Auth::check();
        
        \Log::debug("Session check - ID: {$sessionId}, Authenticated: {$isAuthenticated}");
        
        // If user is already authenticated from session cookie,
        // regenerate session to ensure persistence
        if ($isAuthenticated) {
            $request->session()->regenerate();
            \Log::debug("Session regenerated for user: " . Auth::user()->email);
        } else {
            // Even if not authenticated, ensure session is started
            // This helps with CSRF token generation
            $request->session()->start();
        }
        
        return $next($request);
    }
}
