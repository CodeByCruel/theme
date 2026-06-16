<?php

use Illuminate\Support\Facades\Route;
use Pterodactyl\Http\Controllers\Extensions\Bsdkv1\InstallerController;

/*
|--------------------------------------------------------------------------
| BSDK V1 — Client API Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'servers/{server}/installer',
    'middleware' => ['auth:api', 'throttle:60,1'],
], function () {
    Route::get('/search', [InstallerController::class, 'search']);
    Route::post('/install', [InstallerController::class, 'install']);
    Route::get('/installed', [InstallerController::class, 'installed']);
    Route::delete('/remove', [InstallerController::class, 'remove']);
});
