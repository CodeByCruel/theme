<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BsdkThemeController;
use App\Http\Controllers\Api\Admin\BsdkSettingsController;
use App\Http\Controllers\Api\Admin\BsdkAddonController;

// ── Admin Theme Settings (Blade) ──
Route::group([
    'prefix' => 'admin/bsdk-theme',
    'middleware' => ['auth', 'admin'],
], function () {
    Route::get('/', [BsdkThemeController::class, 'index']);
    Route::post('/', [BsdkThemeController::class, 'update']);
    Route::post('/preset/{preset}', [BsdkThemeController::class, 'applyPreset']);
    Route::post('/reset', [BsdkThemeController::class, 'reset']);
});

// ── Theme Preview API ──
Route::get('/api/admin/bsdk-theme', [BsdkThemeController::class, 'preview'])
    ->middleware(['auth:api']);

// ── Hyper Settings API ──
Route::group([
    'prefix' => 'api/admin/bsdk/settings',
    'middleware' => ['auth:api'],
], function () {
    Route::get('/', [BsdkSettingsController::class, 'index']);
    Route::post('/', [BsdkSettingsController::class, 'update']);
    Route::post('/reset', [BsdkSettingsController::class, 'reset']);
});

// ── Addon Settings API ──
Route::group([
    'prefix' => 'api/admin/bsdk/addons',
    'middleware' => ['auth:api'],
], function () {
    Route::get('/', [BsdkAddonController::class, 'index']);
    Route::post('/{id}/toggle', [BsdkAddonController::class, 'toggle']);
    Route::post('/bulk', [BsdkAddonController::class, 'bulkToggle']);
});
