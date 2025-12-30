<?php

use App\Http\Controllers\Api\Admin\PlatformChangeRequestController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth:api', 'admin'])->group(function () {

    Route::get('platform-change-requests', [PlatformChangeRequestController::class, 'index']);
    Route::get('platform-change-requests/pending', [PlatformChangeRequestController::class, 'pending']);
    Route::get('platform-change-requests/statistics', [PlatformChangeRequestController::class, 'statistics']);
    Route::get('platform-change-requests/{id}', [PlatformChangeRequestController::class, 'show']);
    Route::post('platform-change-requests/{id}/approve', [PlatformChangeRequestController::class, 'approve']);
    Route::post('platform-change-requests/{id}/reject', [PlatformChangeRequestController::class, 'reject']);
    Route::post('platform-change-requests/bulk-approve', [PlatformChangeRequestController::class, 'bulkApprove']);
});

