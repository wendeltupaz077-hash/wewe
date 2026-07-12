<?php

use App\Http\Controllers\Portal\AdminController;
use App\Http\Controllers\Portal\AuthController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\DonorController;
use App\Http\Controllers\Portal\FacilityController;
use App\Http\Controllers\Portal\InventoryController;
use App\Http\Controllers\Portal\ReportController;
use App\Http\Controllers\Portal\RequestController;
use App\Http\Controllers\Portal\SettingsController;
use App\Http\Controllers\Portal\UserController;
use App\Http\Controllers\PublicSiteController;
use App\Http\Middleware\EnsurePortalAccess;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicSiteController::class, 'home'])->name('home');
Route::get('/donor-directory', [PublicSiteController::class, 'donorDirectory'])->name('donor-directory');
Route::get('/about', [PublicSiteController::class, 'about'])->name('about');
Route::get('/faq', [PublicSiteController::class, 'faq'])->name('faq');
Route::get('/blog', [PublicSiteController::class, 'blog'])->name('blog');
Route::get('/contact', [PublicSiteController::class, 'contact'])->name('contact');
Route::get('/features', [PublicSiteController::class, 'features'])->name('features');
Route::get('/how-it-works', [PublicSiteController::class, 'howItWorks'])->name('how-it-works');
Route::get('/stock-status', [PublicSiteController::class, 'stockStatus'])->name('stock-status');
Route::get('/download', [PublicSiteController::class, 'download'])->name('download');
Route::get('/mobile-app', [PublicSiteController::class, 'mobileApp'])->name('mobile-app');

Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
        Route::post('/handle-email', [AuthController::class, 'handleEmail'])->name('handle-email');
        Route::get('/auth-notice', [AuthController::class, 'showNotice'])->name('auth-notice');
        Route::get('/verify-email', [AuthController::class, 'verifyEmail'])->name('verify-email');
        Route::get('/set-password', [AuthController::class, 'showSetPassword'])->name('set-password');
        Route::post('/set-password', [AuthController::class, 'setPassword'])->name('set-password.submit');
        Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
        Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('forgot-password.submit');

    Route::middleware(['auth', EnsurePortalAccess::class])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/first-login', [AuthController::class, 'showFirstLogin'])->name('first-login');
        Route::post('/first-login', [AuthController::class, 'firstLoginChangePassword'])->name('first-login.submit');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index']);
        
        // Admin Management
        Route::prefix('admins')->name('admins.')->middleware('isSuperAdmin')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('index');
            Route::get('/create', [AdminController::class, 'create'])->name('create');
            Route::post('/', [AdminController::class, 'store'])->name('store');
            Route::get('/{admin}/edit', [AdminController::class, 'edit'])->name('edit');
            Route::put('/{admin}', [AdminController::class, 'update'])->name('update');
            Route::post('/{admin}/reset-password', [AdminController::class, 'resetPassword'])->name('reset-password');
            Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('destroy');
        });
        
        Route::get('/stock', [\App\Http\Controllers\Portal\StockController::class, 'index'])->name('stock');
        Route::get('/notifications', [\App\Http\Controllers\Portal\NotificationController::class, 'index'])->name('notifications');
        Route::post('/notifications/{notification}/mark-read', [\App\Http\Controllers\Portal\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/notifications/mark-all-read', [\App\Http\Controllers\Portal\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::get('/api/notifications/unread-count', [\App\Http\Controllers\Portal\NotificationController::class, 'apiUnreadCount'])->name('api.notifications.unread-count');
        Route::get('/api/notifications/latest', [\App\Http\Controllers\Portal\NotificationController::class, 'apiLatest'])->name('api.notifications.latest');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

        // Legal / About pages for portal
        Route::get('/privacy', function () { return view('portal.privacy'); })->name('privacy');
        Route::get('/about-portal', function () { return view('portal.about'); })->name('about');
        Route::prefix('facilities')->name('facilities.')->group(function () {
            Route::get('/', [FacilityController::class, 'index'])->name('index');
            Route::get('/create', [FacilityController::class, 'create'])->name('create');
            Route::post('/', [FacilityController::class, 'store'])->name('store');
            Route::get('/{facility}/edit', [FacilityController::class, 'edit'])->name('edit');
            Route::put('/{facility}', [FacilityController::class, 'update'])->name('update');
            Route::delete('/{facility}', [FacilityController::class, 'destroy'])->name('destroy');
            Route::post('/{facility}/toggle-lock', [FacilityController::class, 'toggleLock'])->name('toggle-lock');
        });

        Route::get('/donors', [DonorController::class, 'index'])->name('donors');
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
        Route::get('/requests', [RequestController::class, 'index'])->name('requests');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });
    });
});
