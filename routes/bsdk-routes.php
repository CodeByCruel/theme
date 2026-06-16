<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BsdkThemeController;

Route::group([
    'prefix' => 'admin/bsdk-theme',
    'middleware' => ['auth', 'admin'],
], function () {
    Route::get('/', [BsdkThemeController::class, 'index']);
    Route::post('/', [BsdkThemeController::class, 'update']);
    Route::post('/preset/{preset}', [BsdkThemeController::class, 'applyPreset']);
    Route::post('/reset', [BsdkThemeController::class, 'reset']);
});

Route::get('/api/admin/bsdk-theme', [BsdkThemeController::class, 'preview'])
    ->middleware(['auth:api']);
