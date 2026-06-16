<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BsdkThemeController;
use App\Http\Controllers\Api\Admin\BsdkSettingsController;
use App\Http\Controllers\Api\Admin\BsdkAddonController;

// ── Admin Theme Settings (Blade) ──
Route::group([
    'prefix' => 'admin/bsdk-theme',
    'as' => 'admin.bsdk-theme',
    'middleware' => ['auth', 'admin'],
], function () {
    Route::get('/', [BsdkThemeController::class, 'index'])->name('index');
    Route::post('/', [BsdkThemeController::class, 'update'])->name('update');
    Route::post('/preset/{preset}', [BsdkThemeController::class, 'applyPreset'])->name('preset');
    Route::post('/reset', [BsdkThemeController::class, 'reset'])->name('reset');
});

// ── Admin Pages (Blade) ──
Route::get('/admin/bsd-settings', [BsdkThemeController::class, 'bsdSettings'])->middleware(['auth', 'admin']);
Route::get('/admin/addon-settings', [BsdkThemeController::class, 'addonSettings'])->middleware(['auth', 'admin']);
Route::get('/admin/servers', [BsdkThemeController::class, 'adminServers'])->middleware(['auth', 'admin']);
Route::get('/admin/users', [BsdkThemeController::class, 'adminUsers'])->middleware(['auth', 'admin']);
Route::get('/admin/nodes', [BsdkThemeController::class, 'adminNodes'])->middleware(['auth', 'admin']);

// ── User Account Pages (Blade) ──
Route::get('/account', [BsdkThemeController::class, 'account'])->middleware(['auth']);
Route::get('/account/api', [BsdkThemeController::class, 'accountApi'])->middleware(['auth']);
Route::get('/account/ssh', [BsdkThemeController::class, 'accountSsh'])->middleware(['auth']);
Route::get('/account/activity', [BsdkThemeController::class, 'accountActivity'])->middleware(['auth']);

// ── User Pages (Blade) ──
Route::get('/staff-request', [BsdkThemeController::class, 'staffRequest'])->middleware(['auth']);
Route::get('/notifications', [BsdkThemeController::class, 'notifications'])->middleware(['auth']);

// ── Theme Preview API ──
Route::get('/api/admin/bsdk-theme', [BsdkThemeController::class, 'preview'])->middleware(['auth:api']);

// ── Settings API ──
Route::group([
    'prefix' => 'api/admin/bsdk/settings',
    'middleware' => ['auth:api'],
], function () {
    Route::get('/', [BsdkSettingsController::class, 'index']);
    Route::post('/', [BsdkSettingsController::class, 'update']);
    Route::post('/reset', [BsdkSettingsController::class, 'reset']);
});

// ── Addon API ──
Route::group([
    'prefix' => 'api/admin/bsdk/addons',
    'middleware' => ['auth:api'],
], function () {
    Route::get('/', [BsdkAddonController::class, 'index']);
    Route::post('/{id}/toggle', [BsdkAddonController::class, 'toggle']);
    Route::post('/bulk', [BsdkAddonController::class, 'bulkToggle']);
});
