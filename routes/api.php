<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// NOTA: Rutas autenticadas de onboarding y prospects ahora en routes/web.php con sesión
// NOTA: /login, /logout y GET /api/user también en routes/web.php

// ====== MÓDULO DE PROSPECTOS Y ONBOARDING (CHEF) ======
// SOLO RUTAS PÚBLICAS - Prospecto (sin autenticación requerida)

Route::post('/prospects', [\App\Http\Controllers\Api\ProspectController::class, 'store']);

// Verificar y usar token de registro
Route::post('/prospect-tokens/verify', [\App\Http\Controllers\Api\ProspectTokenController::class, 'verify']);
Route::post('/prospect-tokens/register', [\App\Http\Controllers\Api\ProspectTokenController::class, 'register']);

// Documentos de prospecto (requieren token válido pero no autenticación)
Route::post('/prospect-tokens/{token}/documents', [\App\Http\Controllers\Api\ProspectDocumentController::class, 'upload']);
Route::get('/prospect-tokens/{token}/documents', [\App\Http\Controllers\Api\ProspectDocumentController::class, 'list']);

