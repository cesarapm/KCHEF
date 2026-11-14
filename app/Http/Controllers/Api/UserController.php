<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController
{
    /**
     * Get authenticated user data
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'message' => 'No autorizado',
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Obtener el prospect relacionado
            $prospect = \App\Models\Prospect::where('user_id', $user->id)->first();

            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'prospect_id' => $prospect?->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('User API error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
