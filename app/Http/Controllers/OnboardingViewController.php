<?php

namespace App\Http\Controllers;

use App\Models\Onboarding;
use Illuminate\Http\Request;

class OnboardingViewController extends Controller
{
    /**
     * Display the onboarding form data as a Blade view
     */
    public function show(Onboarding $onboarding)
    {
        $form = $onboarding->form;

        return view('onboarding.show', [
            'onboarding' => $onboarding,
            'form' => $form,
        ]);
    }
}
