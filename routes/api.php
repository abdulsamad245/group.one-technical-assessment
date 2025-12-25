<?php

use App\Http\Controllers\Api\V1\ActivationController;
use App\Http\Controllers\Api\V1\ApiKeyController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\LicenseController;
use App\Http\Controllers\Api\V1\LicenseKeyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // -------------------------------
    // Public Authentication Routes
    // -------------------------------
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });

    // -------------------------------
    // Public License Key Routes
    // -------------------------------
    Route::get('license-keys/key/{key}', [LicenseKeyController::class, 'showByKey']);

    // -------------------------------
    // Public Activation Routes
    // -------------------------------
    Route::post('activations', [ActivationController::class, 'store']);
    Route::post('deactivations', [ActivationController::class, 'deactivate']);
    Route::get('activations/status', [ActivationController::class, 'status']);

    // -------------------------------
    // Routes requiring Sanctum authentication
    // -------------------------------
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::post('api-keys', [ApiKeyController::class, 'store']);
    });

    // -------------------------------
    // Routes requiring API Key authentication
    // -------------------------------
    Route::middleware(['api_key'])->group(function () {
        // License Management
        Route::apiResource('licenses', LicenseController::class)
            ->only(['index', 'store', 'show', 'update']);
        Route::post('licenses/{id}/renew', [LicenseController::class, 'renew']);
        Route::post('licenses/{id}/suspend', [LicenseController::class, 'suspend']);
        Route::post('licenses/{id}/resume', [LicenseController::class, 'resume']);
        Route::post('licenses/{id}/cancel', [LicenseController::class, 'cancel']);

        // Customer License Routes
        Route::get('customers/licenses', [CustomerController::class, 'licenses']);
    });
});
