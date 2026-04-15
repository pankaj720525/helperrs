<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CategoryManagementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReviewModerationController;
use App\Http\Controllers\Admin\ServiceApprovalController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SubscriptionManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/

// Authentication
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Public Catalog
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::get('/search', [SearchController::class, 'search']);
Route::get('/reviews', [ReviewController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', \App\Http\Middleware\EnsureActiveUser::class, \App\Http\Middleware\DecryptHashIds::class])->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/worker-profile', [ProfileController::class, 'workerProfile']);

    // My Services (Worker)
    Route::get('/my-services', [ServiceController::class, 'myServices']);
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/my-services/{id}', [ServiceController::class, 'update']);
    Route::delete('/my-services/{id}', [ServiceController::class, 'destroy']);

    // Chat
    Route::get('/chats', [ChatController::class, 'index']);
    Route::post('/chats', [ChatController::class, 'store']);
    Route::get('/chats/{id}/messages', [ChatController::class, 'messages']);
    Route::post('/chats/{id}/messages', [ChatController::class, 'sendMessage']);

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    // Preferences
    Route::match(['GET', 'PUT'], '/preferences', [NotificationController::class, 'preferences']);

    // Subscription
    Route::get('/subscription', [SubscriptionController::class, 'show']);
    Route::post('/subscription', [SubscriptionController::class, 'subscribe']);
});

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    // Admin Auth (public)
    Route::post('/login', [AdminAuthController::class, 'login']);

    // Protected Admin Routes
    Route::middleware(['auth:sanctum', \App\Http\Middleware\AdminAuthenticate::class, \App\Http\Middleware\DecryptHashIds::class])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::get('/me', [AdminAuthController::class, 'me']);

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // Service Approval
        Route::get('/services', [ServiceApprovalController::class, 'index']);
        Route::get('/services/{id}', [ServiceApprovalController::class, 'show']);
        Route::put('/services/{id}/approve', [ServiceApprovalController::class, 'approve']);
        Route::put('/services/{id}/reject', [ServiceApprovalController::class, 'reject']);

        // User Management
        Route::get('/users', [UserManagementController::class, 'index']);
        Route::get('/users/{id}', [UserManagementController::class, 'show']);
        Route::put('/users/{id}/toggle-status', [UserManagementController::class, 'toggleStatus']);
        Route::put('/users/{id}/notification-override', [UserManagementController::class, 'notificationOverride']);

        // Category Management
        Route::get('/categories', [CategoryManagementController::class, 'index']);
        Route::post('/categories', [CategoryManagementController::class, 'store']);
        Route::put('/categories/{id}', [CategoryManagementController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryManagementController::class, 'destroy']);

        // Review Moderation
        Route::get('/reviews', [ReviewModerationController::class, 'index']);
        Route::put('/reviews/{id}/moderate', [ReviewModerationController::class, 'moderate']);
        Route::delete('/reviews/{id}', [ReviewModerationController::class, 'destroy']);

        // Subscription Management
        Route::get('/subscriptions', [SubscriptionManagementController::class, 'index']);
        Route::put('/subscriptions/{id}/extend', [SubscriptionManagementController::class, 'extend']);
        Route::get('/subscription-plans', [SubscriptionManagementController::class, 'plans']);
        Route::put('/subscription-plans/{id}', [SubscriptionManagementController::class, 'updatePlan']);

        // Settings
        Route::get('/settings', [SettingsController::class, 'index']);
        Route::put('/settings', [SettingsController::class, 'update']);

        // Audit Logs
        Route::get('/audit-logs', [AuditLogController::class, 'index']);
    });
});
