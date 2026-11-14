<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prospect;
use App\Models\ProspectDocument;
use App\Models\ProspectRegistrationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProspectDocumentController extends Controller
{
    /**
     * Subir documento para un prospect
     * POST /api/prospect-tokens/{token}/documents
     */
    public function upload(Request $request, $token)
    {
        // Validar token
        $registrationToken = ProspectRegistrationToken::where('token', $token)
            ->where('used_at', null)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $prospect = $registrationToken->prospect;

        // Validar request
        $validated = $request->validate([
            'type' => 'required|in:contrato,forma_pago',
            'file' => 'required|file|max:5120', // 5MB
        ]);

        // Crear carpeta si no existe
        $path = "documents/{$prospect->id}";
        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        // Guardar archivo
        $file = $request->file('file');
        $filename = "{$validated['type']}_" . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs($path, $filename, 'public');

        // Crear registro de documento
        $document = $prospect->documents()->updateOrCreate(
            ['type' => $validated['type']],
            [
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'status' => 'pendiente',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Documento subido correctamente',
            'document' => $document,
        ], 201);
    }

    /**
     * Obtener documentos de un prospect
     * GET /api/prospect-tokens/{token}/documents
     */
    public function list($token)
    {
        $registrationToken = ProspectRegistrationToken::where('token', $token)
            ->firstOrFail();

        $documents = $registrationToken->prospect->documents()->get();

        return response()->json([
            'success' => true,
            'documents' => $documents,
        ]);
    }
}
