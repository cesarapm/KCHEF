<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OnboardingViewController;

// ====== AUTENTICACIÓN (Session-based) ======
Route::middleware('web')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/api/user', [AuthController::class, 'user']);

    // ====== RUTAS AUTENTICADAS - Prospecto y Onboarding (con sesión) ======
    Route::middleware(\App\Http\Middleware\AuthenticateApi::class)->group(function () {
        // Prospecto
        Route::get('/api/prospects', [\App\Http\Controllers\Api\ProspectController::class, 'index']);
        Route::get('/api/prospects/{prospect}', [\App\Http\Controllers\Api\ProspectController::class, 'show']);
        Route::post('/api/prospects/{prospect}/generate-token', [\App\Http\Controllers\Api\ProspectController::class, 'generateToken']);
        Route::patch('/api/prospects/{prospect}/status', [\App\Http\Controllers\Api\ProspectController::class, 'updateStatus']);

        // Onboarding
        Route::post('/api/prospects/{prospect}/onboarding/start', [\App\Http\Controllers\Api\OnboardingController::class, 'start']);
        Route::get('/api/prospects/{prospect}/onboarding', [\App\Http\Controllers\Api\OnboardingController::class, 'show']);
        Route::post('/api/onboarding/{onboarding}/requirements', [\App\Http\Controllers\Api\OnboardingController::class, 'addRequirement']);
        Route::patch('/api/onboarding/{onboarding}/status', [\App\Http\Controllers\Api\OnboardingController::class, 'updateStatus']);

        // Documentos de Onboarding
        Route::post('/api/onboarding-documents/upload', [\App\Http\Controllers\Api\OnboardingDocumentController::class, 'upload']);
        Route::post('/api/onboarding-documents/{document}/review', [\App\Http\Controllers\Api\OnboardingDocumentController::class, 'review']);
        Route::get('/api/onboarding-documents/{document}/download', [\App\Http\Controllers\Api\OnboardingDocumentController::class, 'download']);
    });
});

// ====== RUTAS API - Onboarding Form (con CSRF token) ======
Route::middleware(['web', \App\Http\Middleware\AuthenticateApi::class])->group(function () {
    Route::get('/api/onboarding/{onboarding}/form', [\App\Http\Controllers\Api\OnboardingFormController::class, 'show']);
    Route::post('/api/onboarding/{onboarding}/form', [\App\Http\Controllers\Api\OnboardingFormController::class, 'store']);
    Route::patch('/api/onboarding/{onboarding}/form/complete', [\App\Http\Controllers\Api\OnboardingFormController::class, 'complete']);
});

// ====== VISTA BLADE - Onboarding Form ======
Route::get('/onboarding/{onboarding}', [OnboardingViewController::class, 'show'])
    ->name('onboarding.show');

// Redirigir todas las rutas a la vista principal para que Vue Router las maneje
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!onboarding).*$');
