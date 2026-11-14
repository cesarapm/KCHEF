<?php

namespace App\Http\Controllers\Api;

use App\Models\ProspectRegistrationToken;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

class ProspectTokenController
{
    /**
     * Verificar validez del token
     */
    public function verify(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string|size:64',
        ]);

        $token = ProspectRegistrationToken::where('token', $validated['token'])->first();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token no válido',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($token->used_at) {
            return response()->json([
                'success' => false,
                'message' => 'Token ya ha sido utilizado',
            ], Response::HTTP_GONE);
        }

        if (Carbon::now()->isAfter($token->expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Token ha expirado',
            ], Response::HTTP_GONE);
        }

        // Token válido
        return response()->json([
            'success' => true,
            'prospect' => $token->prospect()->select('id', 'full_name', 'email', 'phone')->first(),
        ]);
    }

    /**
     * Usar token para crear usuario/cliente
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string|size:64',
            'password' => 'required|min:8|confirmed',
        ]);

        $registrationToken = ProspectRegistrationToken::where('token', $validated['token'])->first();

        if (!$registrationToken) {
            return response()->json([
                'success' => false,
                'message' => 'Token no válido',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($registrationToken->used_at) {
            return response()->json([
                'success' => false,
                'message' => 'Token ya ha sido utilizado',
            ], Response::HTTP_GONE);
        }

        if (Carbon::now()->isAfter($registrationToken->expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Token ha expirado',
            ], Response::HTTP_GONE);
        }

        try {
            $prospect = $registrationToken->prospect;

            // Crear usuario/cliente
            $user = \App\Models\User::create([
                'name' => $prospect->full_name,
                'email' => $prospect->email,
                'password' => bcrypt($validated['password']),
                'role' => 'cliente',
                'activo' => true,
            ]);

            // Crear perfil de usuario
            \App\Models\UserProfile::create([
                'user_id' => $user->id,
                'phone' => $prospect->phone,
                'city' => $prospect->city,
            ]);

            // Marcar token como usado
            $registrationToken->update([
                'used_at' => Carbon::now(),
            ]);

            // Actualizar prospecto
            $prospect->update([
                'user_id' => $user->id,
                'status' => 'cliente_registrado',
            ]);

            // Log
            $prospect->activityLogs()->create([
                'action' => 'client_registered',
                'description' => 'Prospecto registrado como cliente',
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Generar token API Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

            // Obtener onboarding status
            $onboarding = $prospect->onboarding;

            return response()->json([
                'success' => true,
                'message' => 'Cliente registrado exitosamente',
                'user_id' => $user->id,
                'prospect_id' => $prospect->id,
                'onboarding_status' => $onboarding ? $onboarding->status : 'configuracion_pendiente',
                'auth_token' => $token,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar cliente: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
