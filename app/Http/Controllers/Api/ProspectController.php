<?php

namespace App\Http\Controllers\Api;

use App\Models\Prospect;
use App\Models\Onboarding;
use App\Models\ProspectRegistrationToken;
use App\Mail\RegistrationLinkMail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ProspectController
{
    /**
     * Crear un nuevo prospecto (formulario público)
     * Automáticamente inicia el proceso de onboarding
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:prospects,email',
            'city' => 'nullable|string|max:255',
        ]);

        try {
            // Crear prospecto
            $prospect = Prospect::create([
                ...$validated,
                'status' => 'pendiente',
            ]);

            // ✅ Crear automáticamente el onboarding
            $onboarding = Onboarding::create([
                'prospect_id' => $prospect->id,
                'status' => 'configuracion_pendiente', // Estado inicial
                'started_at' => null,
            ]);

            // Log de actividad
            $prospect->activityLogs()->create([
                'action' => 'prospect_created',
                'description' => 'Prospecto creado desde formulario público',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Log de onboarding iniciado
            $prospect->activityLogs()->create([
                'action' => 'onboarding_created',
                'description' => 'Proceso de onboarding automáticamente creado',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Prospecto creado y onboarding iniciado',
                'prospect_id' => $prospect->id,
                'onboarding_id' => $onboarding->id,
                'onboarding_status' => $onboarding->status,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear prospecto: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Obtener prospecto por ID (requiere autenticación)
     */
    public function show(Prospect $prospect)
    {
        // Verificar permiso (admin o propietario)
        $user = auth()->user();
        if ($user && ($user->role === 'admin' || $prospect->user_id === $user->id)) {
            return response()->json([
                'prospect' => $prospect->load('activityLogs', 'onboarding'),
            ]);
        }

        return response()->json([
            'message' => 'No autorizado',
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * Listar prospectos asignados al usuario (requiere autenticación)
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user || !in_array($user->role, ['admin', 'asesor'])) {
            return response()->json([
                'message' => 'No autorizado',
            ], Response::HTTP_FORBIDDEN);
        }

        $query = Prospect::query();

        // Filtros
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($user->role === 'asesor') {
            $query->where('user_id', $user->id);
        }

        $prospects = $query->with('activityLogs', 'onboarding')
            ->paginate(15);

        return response()->json([
            'prospects' => $prospects,
        ]);
    }

    /**
     * Generar token de invitación para prospecto
     */
    public function generateToken(Request $request, Prospect $prospect)
    {
        $user = auth()->user();

        // Verificar permiso
        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => 'Solo administradores pueden generar tokens',
            ], Response::HTTP_FORBIDDEN);
        }

        try {
            $token = Str::random(64);
            $expiresAt = Carbon::now()->addDays(7); // Token válido 7 días

            $registrationToken = $prospect->registrationTokens()->create([
                'token' => $token,
                'expires_at' => $expiresAt,
            ]);

            // Cambiar estado a 'enlace_enviado'
            $prospect->update(['status' => 'enlace_enviado']);

            // Enviar email con link
            Mail::to($prospect->email)->send(new RegistrationLinkMail($prospect, $token));

            // Log
            $prospect->activityLogs()->create([
                'action' => 'token_generated',
                'description' => 'Token de invitación generado y email enviado',
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'new_value' => ['status' => 'enlace_enviado'],
            ]);

            return response()->json([
                'success' => true,
                'token' => $token,
                'expires_at' => $expiresAt,
                'message' => 'Token generado y email enviado al prospecto'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar token: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Cambiar estado de prospecto (solo admin)
     */
    public function updateStatus(Request $request, Prospect $prospect)
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => 'No autorizado',
            ], Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validate([
            'status' => 'required|in:pendiente,aprobado,enlace_enviado,cliente_registrado,en_onboarding,completado,rechazado',
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $prospect->status;

        $prospect->update([
            'status' => $validated['status'],
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'user_id' => $user->id,
        ]);

        // Log
        $prospect->activityLogs()->create([
            'action' => 'status_changed',
            'description' => "Estado cambiado de {$oldStatus} a {$validated['status']}",
            'old_value' => ['status' => $oldStatus],
            'new_value' => ['status' => $validated['status']],
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'prospect' => $prospect,
        ]);
    }
}
