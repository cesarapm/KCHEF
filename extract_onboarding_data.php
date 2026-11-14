<?php

/**
 * Script para extraer datos del onboarding completado (ID 15)
 * Guarda la información en archivos JSON y CSV
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Onboarding;
use App\Models\OnboardingForm;
use App\Models\OnboardingDocument;

// ID del onboarding a extraer
$onboardingId = 15;

// Directorio donde guardar los archivos
$exportDir = storage_path('exports/onboarding_' . $onboardingId);
if (!is_dir($exportDir)) {
    mkdir($exportDir, 0755, true);
}

try {
    // Obtener el onboarding
    $onboarding = Onboarding::with([
        'prospect',
        'prospect.user',
        'user',
        'requirements',
        'documents',
        'form'
    ])->find($onboardingId);

    if (!$onboarding) {
        echo "❌ Onboarding #$onboardingId no encontrado\n";
        exit(1);
    }

    echo "✅ Onboarding encontrado: ID #$onboardingId\n";
    echo "   Status: {$onboarding->status}\n";
    echo "   Prospect: {$onboarding->prospect->name}\n";
    echo "   Completado: " . ($onboarding->completed_at ? $onboarding->completed_at->format('Y-m-d H:i:s') : 'No') . "\n\n";

    // 1. Guardar información general en JSON
    $generalData = [
        'id' => $onboarding->id,
        'status' => $onboarding->status,
        'prospect' => [
            'id' => $onboarding->prospect->id,
            'name' => $onboarding->prospect->name,
            'email' => $onboarding->prospect->email,
            'phone' => $onboarding->prospect->phone ?? null,
        ],
        'assigned_to' => $onboarding->user ? [
            'id' => $onboarding->user->id,
            'name' => $onboarding->user->name,
            'email' => $onboarding->user->email,
        ] : null,
        'dates' => [
            'started_at' => $onboarding->started_at,
            'completed_at' => $onboarding->completed_at,
            'created_at' => $onboarding->created_at,
            'updated_at' => $onboarding->updated_at,
        ],
    ];

    file_put_contents(
        $exportDir . '/general_info.json',
        json_encode($generalData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
    echo "📄 Guardado: general_info.json\n";

    // 2. Guardar datos del formulario
    if ($onboarding->form) {
        $formData = $onboarding->form->toArray();
        file_put_contents(
            $exportDir . '/onboarding_form.json',
            json_encode($formData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
        echo "📄 Guardado: onboarding_form.json\n";

        // Crear versión legible del formulario
        $readableForm = "=== FORMULARIO DE ONBOARDING ===\n\n";
        $readableForm .= "INFORMACIÓN BÁSICA\n";
        $readableForm .= "─────────────────\n";
        $readableForm .= "Clientes Principales: " . ($formData['primary_clients'] ?? 'N/A') . "\n";
        $readableForm .= "Fecha de Inicio: " . ($formData['start_date'] ?? 'N/A') . "\n";
        $readableForm .= "Dirección: " . ($formData['household_address'] ?? 'N/A') . "\n";
        $readableForm .= "Adultos: " . ($formData['number_of_adults'] ?? '0') . "\n";
        $readableForm .= "Niños: " . ($formData['number_of_kids'] ?? '0') . "\n";
        $readableForm .= "Edades de niños: " . ($formData['kids_ages'] ?? 'N/A') . "\n\n";

        $readableForm .= "OBJETIVOS DEL HOGAR\n";
        $readableForm .= "──────────────────\n";
        if ($formData['household_goals']) {
            $goals = json_decode($formData['household_goals'], true);
            foreach ((array)$goals as $goal) {
                $readableForm .= "• $goal\n";
            }
        }
        if ($formData['household_goals_other'] ?? null) {
            $readableForm .= "• (Otro) " . $formData['household_goals_other'] . "\n";
        }
        $readableForm .= "\n";

        $readableForm .= "PREFERENCIAS DIETÉTICAS\n";
        $readableForm .= "──────────────────────\n";
        if ($formData['dietary_preferences']) {
            $prefs = json_decode($formData['dietary_preferences'], true);
            foreach ((array)$prefs as $pref) {
                $readableForm .= "• $pref\n";
            }
        }
        if ($formData['dietary_preferences_other'] ?? null) {
            $readableForm .= "• (Otro) " . $formData['dietary_preferences_other'] . "\n";
        }
        $readableForm .= "\n";

        $readableForm .= "PREFERENCIAS NUTRICIONALES\n";
        $readableForm .= "──────────────────────────\n";
        if ($formData['nutrition_preferences']) {
            $prefs = json_decode($formData['nutrition_preferences'], true);
            foreach ((array)$prefs as $pref) {
                $readableForm .= "• $pref\n";
            }
        }
        if ($formData['nutrition_preferences_other'] ?? null) {
            $readableForm .= "• (Otro) " . $formData['nutrition_preferences_other'] . "\n";
        }
        $readableForm .= "\n";

        $readableForm .= "ALIMENTOS A EVITAR\n";
        $readableForm .= "──────────────────\n";
        if ($formData['foods_to_avoid']) {
            $avoid = json_decode($formData['foods_to_avoid'], true);
            foreach ((array)$avoid as $food) {
                $readableForm .= "• $food\n";
            }
        }
        $readableForm .= "\n";

        $readableForm .= "CUISINAS FAVORITAS\n";
        $readableForm .= "──────────────────\n";
        if ($formData['favorite_cuisines']) {
            $cuisines = json_decode($formData['favorite_cuisines'], true);
            foreach ((array)$cuisines as $cuisine) {
                $readableForm .= "• $cuisine\n";
            }
        }
        if ($formData['favorite_cuisines_other'] ?? null) {
            $readableForm .= "• (Otro) " . $formData['favorite_cuisines_other'] . "\n";
        }
        $readableForm .= "\n";

        $readableForm .= "RESTAURANTES FAVORITOS\n";
        $readableForm .= "──────────────────────\n";
        if ($formData['favorite_restaurants']) {
            $restaurants = json_decode($formData['favorite_restaurants'], true);
            foreach ((array)$restaurants as $restaurant) {
                $readableForm .= "• $restaurant\n";
            }
        }

        file_put_contents($exportDir . '/form_readable.txt', $readableForm);
        echo "📄 Guardado: form_readable.txt\n";
    }

    // 3. Guardar información de requisitos
    if ($onboarding->requirements->count() > 0) {
        $requirementsData = [];
        foreach ($onboarding->requirements as $req) {
            $requirementsData[] = [
                'id' => $req->id,
                'name' => $req->name,
                'description' => $req->description,
                'status' => $req->status,
                'documents_count' => $req->documents->count(),
            ];
        }
        file_put_contents(
            $exportDir . '/requirements.json',
            json_encode($requirementsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
        echo "📄 Guardado: requirements.json\n";
    }

    // 4. Guardar información de documentos
    if ($onboarding->documents->count() > 0) {
        $documentsData = [];
        foreach ($onboarding->documents as $doc) {
            $documentsData[] = [
                'id' => $doc->id,
                'requirement_id' => $doc->requirement_id,
                'file_name' => $doc->file_name,
                'file_path' => $doc->file_path,
                'file_size' => $doc->file_size,
                'file_type' => $doc->file_type,
                'status' => $doc->status,
                'uploaded_at' => $doc->created_at,
                'revisions_count' => $doc->revisions->count(),
            ];
        }
        file_put_contents(
            $exportDir . '/documents.json',
            json_encode($documentsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
        echo "📄 Guardado: documents.json\n";

        // Crear CSV de documentos
        $csv = "ID,Nombre del Archivo,Tipo,Tamaño (bytes),Estado,Fecha de Carga,Revisiones\n";
        foreach ($onboarding->documents as $doc) {
            $csv .= "\"{$doc->id}\",\"{$doc->file_name}\",\"{$doc->file_type}\",\"{$doc->file_size}\",\"{$doc->status}\",\"{$doc->created_at}\",\"{$doc->revisions->count()}\"\n";
        }
        file_put_contents($exportDir . '/documents.csv', $csv);
        echo "📄 Guardado: documents.csv\n";
    }

    // 5. Crear un resumen general
    $summary = "RESUMEN DEL ONBOARDING #$onboardingId\n";
    $summary .= "=" . str_repeat("=", 60) . "\n\n";
    $summary .= "ESTADO GENERAL\n";
    $summary .= "─" . str_repeat("─", 60) . "\n";
    $summary .= "Prospect: {$onboarding->prospect->name}\n";
    $summary .= "Status: {$onboarding->status}\n";
    $summary .= "Iniciado: " . ($onboarding->started_at ? $onboarding->started_at->format('Y-m-d H:i:s') : 'No iniciado') . "\n";
    $summary .= "Completado: " . ($onboarding->completed_at ? $onboarding->completed_at->format('Y-m-d H:i:s') : 'No completado') . "\n";
    $summary .= "Asignado a: " . ($onboarding->user ? $onboarding->user->name : 'Sin asignar') . "\n\n";

    $summary .= "REQUISITOS: " . $onboarding->requirements->count() . " requerimientos\n";
    $summary .= "DOCUMENTOS: " . $onboarding->documents->count() . " documentos\n";
    $summary .= "FORMULARIO: " . ($onboarding->form ? "Completado" : "Pendiente") . "\n\n";

    $summary .= "ARCHIVOS GENERADOS\n";
    $summary .= "─" . str_repeat("─", 60) . "\n";
    $summary .= "✓ general_info.json      - Información general del onboarding\n";
    $summary .= "✓ onboarding_form.json   - Datos del formulario (JSON)\n";
    $summary .= "✓ form_readable.txt      - Formulario en formato legible\n";
    $summary .= "✓ requirements.json      - Lista de requisitos\n";
    $summary .= "✓ documents.json         - Detalles de documentos\n";
    $summary .= "✓ documents.csv          - Documentos en formato CSV\n";
    $summary .= "✓ RESUMEN.txt            - Este archivo\n\n";

    $summary .= "Ubicación: " . $exportDir . "\n";
    $summary .= "Fecha de exportación: " . date('Y-m-d H:i:s') . "\n";

    file_put_contents($exportDir . '/RESUMEN.txt', $summary);
    echo "📄 Guardado: RESUMEN.txt\n\n";

    echo "✅ Datos del onboarding #$onboardingId exportados exitosamente\n";
    echo "📁 Ubicación: $exportDir\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
