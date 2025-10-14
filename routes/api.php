<?php

use App\Http\Controllers\CpvSuggestController;
use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

// Health check endpoints (no auth required)
Route::get('/healthz', [HealthController::class, 'health']);
Route::get('/readyz', [HealthController::class, 'ready']);

// API v1 routes
Route::prefix('v1')->group(function () {
    // CPV suggestion endpoint (with auth and rate limiting)
    // TODO: Enable auth:sanctum in production
    Route::middleware(['throttle:60,1'])
        ->post('/cpv/suggest', CpvSuggestController::class);
});
