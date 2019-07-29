<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::namespace('App\\Http\\Controllers\\Passport')->group(
    function (): void {
        Route::get('/authorize', 'AuthorizationController@authorize')
            ->name('authorizations.authorize')
            ->middleware(['web', 'auth:web']);
    }
);

Route::namespace('Laravel\\Passport\\Http\\Controllers')->group(
    function (): void {
        Route::post('/authorize', 'ApproveAuthorizationController@approve')
            ->name('authorizations.approve')
            ->middleware(['web', 'auth:web']);

        Route::delete('/authorize', 'DenyAuthorizationController@deny')
            ->name('authorizations.deny')
            ->middleware(['web', 'auth:web']);

        Route::post('/token', 'AccessTokenController@issueToken')
            ->name('token')
            ->middleware('throttle');

        Route::get('/tokens', 'AuthorizedAccessTokenController@forUser')
            ->name('tokens.index')
            ->middleware(['web', 'auth:web']);

        Route::delete('/tokens/{token_id}', 'AuthorizedAccessTokenController@destroy')
            ->name('tokens.destroy')
            ->middleware(['web', 'auth:web']);
    }
);
