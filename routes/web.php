<?php

use App\Http\Controllers\Web\AdminWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'app' => config('app.name', 'Soul Connect'),
        'version' => '1.0.0',
        'status' => 'active',
        'documentation' => '/api/v1',
        'admin_portal' => '/admin',
    ]);
});

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminWebController::class, 'login'])->name('admin.login');
    Route::post('login', [AdminWebController::class, 'handleLogin'])->name('admin.login.submit');
    Route::post('logout', [AdminWebController::class, 'logout'])->name('admin.logout');

    // Authenticated Admin Routes
    Route::middleware(['auth:admin_web'])->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });
        Route::get('dashboard', [AdminWebController::class, 'dashboard'])->name('admin.dashboard');

        // Users & Profiles
        Route::get('users', [AdminWebController::class, 'users'])->name('admin.users');
        Route::get('users/{user}', [AdminWebController::class, 'showUser'])->name('admin.users.show');
        Route::put('users/{user}', [AdminWebController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('users/{user}', [AdminWebController::class, 'deleteUser'])->name('admin.users.delete');
        Route::post('users/{user}/wallet/adjust', [AdminWebController::class, 'adjustWallet'])->name('admin.users.wallet.adjust');
        Route::patch('users/{user}/status', [AdminWebController::class, 'updateStatus'])->name('admin.users.status');

        // Financial Ledger & Transactions
        Route::get('wallets', [AdminWebController::class, 'wallets'])->name('admin.wallets');

        // Calls
        Route::get('calls', [AdminWebController::class, 'calls'])->name('admin.calls');

        // Reports
        Route::get('reports', [AdminWebController::class, 'reports'])->name('admin.reports');
        Route::patch('reports/{report}', [AdminWebController::class, 'reviewReport'])->name('admin.reports.review');

        // Packages Store
        Route::get('packages', [AdminWebController::class, 'packages'])->name('admin.packages');
        Route::post('packages', [AdminWebController::class, 'storePackage'])->name('admin.packages.store');
        Route::delete('packages/{package}', [AdminWebController::class, 'deletePackage'])->name('admin.packages.delete');

        // Gift Catalog
        Route::get('gifts', [AdminWebController::class, 'gifts'])->name('admin.gifts');
        Route::post('gifts', [AdminWebController::class, 'storeGift'])->name('admin.gifts.store');
        Route::delete('gifts/{gift}', [AdminWebController::class, 'deleteGift'])->name('admin.gifts.delete');

        // Subscriptions
        Route::get('subscriptions', [AdminWebController::class, 'subscriptions'])->name('admin.subscriptions');

        // System Settings
        Route::get('settings', [AdminWebController::class, 'settings'])->name('admin.settings');
        Route::post('settings', [AdminWebController::class, 'saveSettings'])->name('admin.settings.save');
    });
});
