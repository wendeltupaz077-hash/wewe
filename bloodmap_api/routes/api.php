<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\DonorApiController;
use App\Http\Controllers\Api\EmergencyRequestApiController;
use App\Http\Controllers\Api\FacilityApiController;
use App\Http\Controllers\Api\InventoryApiController;
use App\Http\Controllers\Api\NotificationApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/auth/otp/send', [AuthApiController::class, 'sendOtp']);
    Route::post('/auth/otp/verify', [AuthApiController::class, 'verifyOtp']);

    Route::get('/facilities', [FacilityApiController::class, 'index']);
    Route::get('/facilities/{facility}', [FacilityApiController::class, 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthApiController::class, 'me']);
        Route::post('/auth/register-profile', [AuthApiController::class, 'registerProfile']);

        Route::get('/inventory', [InventoryApiController::class, 'index']);
        Route::post('/inventory', [InventoryApiController::class, 'store']);
        Route::delete('/inventory/{inventory}', [InventoryApiController::class, 'destroy']);

        Route::post('/donors/apply', [DonorApiController::class, 'apply']);

        Route::get('/emergency-requests', [EmergencyRequestApiController::class, 'index']);
        Route::post('/emergency-requests', [EmergencyRequestApiController::class, 'store']);

        Route::get('/notifications', [NotificationApiController::class, 'index']);
        Route::post('/notifications/{notification}/mark-read', [NotificationApiController::class, 'markAsRead']);
        Route::post('/notifications/mark-all-read', [NotificationApiController::class, 'markAllAsRead']);
    });
});
