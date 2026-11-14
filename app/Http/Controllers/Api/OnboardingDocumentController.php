<?php

namespace App\Http\Controllers\Api;

use App\Models\OnboardingDocument;
use App\Models\OnboardingRequirement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class OnboardingDocumentController
{
    /**
     * Cargar documento para requisito
     */
    public function upload(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'No autenticado',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $validated = $request->validate([
            'requirement_id' => 'required|exists:onboarding_requirements,id',
            'file' => 'required|file|max:10240', // 10MB
        ]);

        try {
            $requirement = OnboardingRequirement::findOrFail($validated['requirement_id']);
            $file = $request->file('file');

            // Validar formato si está configurado
            if ($requirement->allowed_formats) {
                $formats = json_decode($requirement->allowed_formats);
                $ext = $file->getClientOriginalExtension();
                if (!in_array($ext, $formats)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Formato de archivo no permitido',
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }

            // Validar tamaño si está configurado
            if ($requirement->max_file_size && $file->getSize() > $requirement->max_file_size) {
                return response()->json([
                    'success' => false,
                    'message' => 'Archivo demasiado grande',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Guardar archivo
            $filePath = Storage::disk('private')->putFile('onboarding-documents', $file);

            // Crear registro de documento
            $document = OnboardingDocument::create([
                'requirement_id' => $requirement->id,
                'user_id' => $user->id,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'status' => 'cargado',
                'uploaded_at' => Carbon::now(),
            ]);

            // Log
            $requirement->onboarding->prospect->activityLogs()->create([
                'action' => 'document_uploaded',
                'description' => "Documento '{$file->getClientOriginalName()}' cargado para: {$requirement->title}",
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'document' => $document,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar documento: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Revisar documento (admin)
     */
    public function review(Request $request, OnboardingDocument $document)
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => 'No autorizado',
            ], Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validate([
            'status' => 'required|in:aprobado,rechazado,requiere_correccion',
            'comment' => 'nullable|string|max:1000',
        ]);

        try {
            // Crear revisión
            $revision = $document->revisions()->create([
                'admin_user_id' => $user->id,
                'status' => $validated['status'],
                'comment' => $validated['comment'] ?? null,
            ]);

            // Actualizar estado del documento
            $document->update(['status' => $validated['status']]);

            // Log
            $document->requirement->onboarding->prospect->activityLogs()->create([
                'action' => 'document_reviewed',
                'description' => "Documento revisado: {$validated['status']}",
                'old_value' => ['status' => 'en_revision'],
                'new_value' => ['status' => $validated['status']],
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'revision' => $revision,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Descargar documento (con permisos)
     */
    public function download(OnboardingDocument $document)
    {
        $user = auth()->user();

        // Verificar permisos
        if (!$user || !($user->role === 'admin' || $user->id === $document->user_id)) {
            return response()->json([
                'message' => 'No autorizado',
            ], Response::HTTP_FORBIDDEN);
        }

        if (!Storage::disk('private')->exists($document->file_path)) {
            return response()->json([
                'message' => 'Archivo no encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        return Storage::disk('private')->download(
            $document->file_path,
            $document->file_name
        );
    }
}
