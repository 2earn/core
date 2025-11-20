<?php

/**
 * Sample Routes for Platform Change Request Management
 *
 * Add these routes to your routes file (e.g., routes/api.php)
 * Adjust middleware and prefixes according to your application structure
 */

use App\Http\Controllers\Api\Admin\PlatformChangeRequestController;
use Illuminate\Support\Facades\Route;

// Admin routes for managing platform change requests
// These should be protected with admin middleware
Route::prefix('admin')->middleware(['auth:api', 'admin'])->group(function () {

    // Get all change requests (with optional filtering)
    Route::get('platform-change-requests', [PlatformChangeRequestController::class, 'index']);

    // Get pending change requests only
    Route::get('platform-change-requests/pending', [PlatformChangeRequestController::class, 'pending']);

    // Get statistics
    Route::get('platform-change-requests/statistics', [PlatformChangeRequestController::class, 'statistics']);

    // Get a specific change request
    Route::get('platform-change-requests/{id}', [PlatformChangeRequestController::class, 'show']);

    // Approve a change request
    Route::post('platform-change-requests/{id}/approve', [PlatformChangeRequestController::class, 'approve']);

    // Reject a change request
    Route::post('platform-change-requests/{id}/reject', [PlatformChangeRequestController::class, 'reject']);

    // Bulk approve multiple change requests
    Route::post('platform-change-requests/bulk-approve', [PlatformChangeRequestController::class, 'bulkApprove']);
});

/**
 * API ENDPOINTS DOCUMENTATION
 *
 * 1. GET /api/admin/platform-change-requests
 *    Query params: ?status=pending&platform_id=1&page=1&per_page=20
 *    Response: List of change requests with pagination
 *
 * 2. GET /api/admin/platform-change-requests/pending
 *    Query params: ?page=1&per_page=20&platform_id=1
 *    Response: List of pending change requests only
 *
 * 3. GET /api/admin/platform-change-requests/statistics
 *    Response: Statistics about change requests (counts, recent items)
 *
 * 4. GET /api/admin/platform-change-requests/{id}
 *    Response: Single change request with full details
 *
 * 5. POST /api/admin/platform-change-requests/{id}/approve
 *    Body: { "reviewed_by": 1 }
 *    Response: Approved change request and updated platform
 *
 * 6. POST /api/admin/platform-change-requests/{id}/reject
 *    Body: { "reviewed_by": 1, "rejection_reason": "Invalid changes" }
 *    Response: Rejected change request
 *
 * 7. POST /api/admin/platform-change-requests/bulk-approve
 *    Body: { "reviewed_by": 1, "change_request_ids": [1, 2, 3] }
 *    Response: Summary of approved and failed requests
 */

