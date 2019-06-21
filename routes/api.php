<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function (): void {
    Route::apiResource('admins', 'AdminController');

    Route::apiResource('audits', 'AuditController')
        ->only('index', 'show');

    Route::apiResource('contributions', 'ContributionController');
    Route::put('contributions/{contribution}/approve', 'Contribution\\ApproveController')
        ->name('contributions.approve');
    Route::put('contributions/{contribution}/reject', 'Contribution\\RejectController')
        ->name('contributions.reject');

    Route::apiResource('end-users', 'EndUserController');

    Route::post('exports/{export}/request', 'Export\\RequestController')
        ->name('exports.request');

    Route::get('files/{file}/download', 'File\\DownloadController')
        ->name('files.download');
    Route::post('files/{file}/request', 'File\\RequestController')
        ->name('files.request');

    Route::apiResource('notifications', 'NotificationController')
        ->only('index', 'show');

    Route::get('settings', 'SettingController@index')
        ->name('settings.index');
    Route::put('settings', 'SettingController@update')
        ->name('settings.update');

    Route::apiResource('tags', 'TagController')
        ->only('index', 'store', 'show', 'destroy');
});
