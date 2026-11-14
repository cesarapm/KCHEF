<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Manejar el login de usuarios
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'password.required' => 'Password is required',
        ]);

        // Intentar autenticar
        if (Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ])) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Obtener el prospect relacionado
            $prospect = \App\Models\Prospect::where('user_id', $user->id)->first();

            // Redirigir al dashboard o página anterior
            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'prospect_id' => $prospect?->id,
            ]);
        }

        // Si falla, devolver error
        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Logout the user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Session closed']);
    }

    /**
     * Get current user
     */
    public function user(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Not authenticated',
            ], 401);
        }

        $user = $request->user();

        // Get related prospect
        $prospect = \App\Models\Prospect::where('user_id', $user->id)->first();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'prospect_id' => $prospect?->id,
        ]);
    }
}
