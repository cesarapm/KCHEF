<?php

namespace App\Http\Controllers\Api;

use App\Models\OnboardingForm;
use App\Models\Onboarding;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

class OnboardingFormController
{
    /**
     * Obtener formulario de onboarding por onboarding_id
     */
    public function show(Onboarding $onboarding)
    {
        $form = $onboarding->form;

        if (!$form) {
            // Si no existe, crear uno nuevo en draft
            $form = OnboardingForm::create([
                'onboarding_id' => $onboarding->id,
                'status' => 'draft',
            ]);
        }

        return response()->json([
            'success' => true,
            'form' => $form,
        ]);
    }

    /**
     * Guardar/Actualizar formulario de onboarding
     */
    public function store(Request $request, Onboarding $onboarding)
    {
        // Procesar los datos antes de validar
        $data = $request->all();
        
        // Convertir primary_clients de array de objetos a array de strings
        if (isset($data['primary_clients']) && is_array($data['primary_clients'])) {
            $data['primary_clients'] = array_map(function($client) {
                return is_array($client) ? ($client['name'] ?? '') : (is_string($client) ? $client : '');
            }, $data['primary_clients']);
            $data['primary_clients'] = array_filter($data['primary_clients']);
        }

        $validated = $request->validate([
            'primary_clients' => 'nullable|array',
            'start_date' => 'nullable|date',
            'household_address' => 'nullable|string',
            'number_of_adults' => 'nullable|integer|min:0',
            'number_of_kids' => 'nullable|integer|min:0',
            'kids_ages' => 'nullable|string',
            'household_goals' => 'nullable|array',
            'household_goals_other' => 'nullable|string',
            'dietary_preferences' => 'nullable|array',
            'dietary_preferences_other' => 'nullable|string',
            'nutrition_preferences' => 'nullable|array',
            'nutrition_preferences_other' => 'nullable|string',
            'foods_to_avoid' => 'nullable|array',
            'favorite_cuisines' => 'nullable|array',
            'favorite_cuisines_other' => 'nullable|string',
            'favorite_restaurants' => 'nullable|array',
            'children_preferences' => 'nullable',
            'status' => 'nullable|in:draft,completed,reviewed',
        ]);

        // Usar los datos procesados
        $validated['primary_clients'] = $data['primary_clients'] ?? [];

        $form = $onboarding->form ?? new OnboardingForm(['onboarding_id' => $onboarding->id]);

        $form->fill($validated);

        // Si status es completed, registrar fecha de completación
        if ($validated['status'] === 'completed' && !$form->completed_at) {
            $form->completed_at = Carbon::now();
        }

        $form->save();

        return response()->json([
            'success' => true,
            'message' => 'Formulario guardado correctamente',
            'form' => $form,
        ], Response::HTTP_OK);
    }

    /**
     * Marcar formulario como completado
     */
    public function complete(Onboarding $onboarding)
    {
        $form = $onboarding->form;

        if (!$form) {
            return response()->json([
                'success' => false,
                'message' => 'Formulario no encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        $form->update([
            'status' => 'completed',
            'completed_at' => Carbon::now(),
        ]);

        // Actualizar estado del onboarding
        $onboarding->update([
            'status' => 'completado',
            'completed_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Onboarding completado',
            'form' => $form,
        ], Response::HTTP_OK);
    }
}
