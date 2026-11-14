<?php

namespace App\Http\Controllers\Api;

use App\Models\Onboarding;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

class OnboardingController
{
    /**
     * Obtener requisitos de onboarding para un prospecto
     */
    public function show(Prospect $prospect)
    {
        try {
            $user = auth()->user();

            // Verificar permiso
            if (!$user) {
                return response()->json([
                    'message' => 'No autorizado',
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Verificar que el usuario sea el dueño del prospect o admin
            if ($user->id !== $prospect->user_id && $user->role !== 'admin') {
                return response()->json([
                    'message' => 'No tienes permisos',
                ], Response::HTTP_FORBIDDEN);
            }

            // Obtener o crear onboarding
            $onboarding = $prospect->onboarding;

            if (!$onboarding) {
                // Crear onboarding si no existe
                $onboarding = Onboarding::create([
                    'prospect_id' => $prospect->id,
                    'user_id' => $user->id,
                    'status' => 'en_onboarding',
                    'started_at' => Carbon::now(),
                ]);
            }

            return response()->json([
                'onboarding' => [
                    'id' => $onboarding->id,
                    'prospect_id' => $onboarding->prospect_id,
                    'status' => $onboarding->status,
                    'started_at' => $onboarding->started_at,
                    'completed_at' => $onboarding->completed_at,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Onboarding show error: ' . $e->getMessage() . ' - ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Iniciar onboarding para un prospecto (admin)
     */
    public function start(Request $request, Prospect $prospect)
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => 'Solo administradores pueden iniciar onboarding',
            ], Response::HTTP_FORBIDDEN);
        }

        try {
            $onboarding = $prospect->onboarding;

            if ($onboarding) {
                return response()->json([
                    'message' => 'Onboarding ya iniciado',
                ], Response::HTTP_CONFLICT);
            }

            // Crear onboarding
            $onboarding = Onboarding::create([
                'prospect_id' => $prospect->id,
                'user_id' => $user->id,
                'status' => 'configuracion_pendiente',
                'started_at' => Carbon::now(),
            ]);

            // Log
            $prospect->activityLogs()->create([
                'action' => 'onboarding_started',
                'description' => 'Onboarding iniciado',
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'onboarding' => $onboarding,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Agregar requisito de documentación
     */
    public function addRequirement(Request $request, Onboarding $onboarding)
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => 'No autorizado',
            ], Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'required' => 'boolean',
            'allowed_formats' => 'nullable|json',
            'max_file_size' => 'nullable|integer',
            'order' => 'integer',
        ]);

        try {
            $requirement = $onboarding->requirements()->create($validated);

            return response()->json([
                'success' => true,
                'requirement' => $requirement,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Cambiar estado de onboarding
     */
    public function updateStatus(Request $request, Onboarding $onboarding)
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => 'No autorizado',
            ], Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validate([
            'status' => 'required|in:configuracion_pendiente,registro_pendiente,documentos_pendientes,en_revision,requiere_correcciones,completado',
        ]);

        $oldStatus = $onboarding->status;

        if ($validated['status'] === 'completado') {
            $onboarding->update([
                'status' => $validated['status'],
                'completed_at' => Carbon::now(),
            ]);

            // Actualizar prospecto
            $onboarding->prospect->update(['status' => 'completado']);
        } else {
            $onboarding->update(['status' => $validated['status']]);
        }

        // Log
        $onboarding->prospect->activityLogs()->create([
            'action' => 'onboarding_status_changed',
            'description' => "Onboarding: {$oldStatus} → {$validated['status']}",
            'old_value' => ['status' => $oldStatus],
            'new_value' => ['status' => $validated['status']],
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'onboarding' => $onboarding,
        ]);
    }
}
