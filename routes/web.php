<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'LandingController')
    ->name('landing');

Route::prefix('docs')
    ->group(
        function (): void {
            Route::get('/', 'DocsController@index')
                ->name('docs.index');
            Route::get('/openapi.json', 'DocsController@openApi')
                ->name('docs.openapi');
        }
    );

Route::prefix('auth/admin')
    ->as('auth.admin.')
    ->namespace('Auth\\Admin')
    ->group(
        function (): void {
            Route::get('login', 'LoginController@showLoginForm')
                ->name('login');
            Route::post('login', 'LoginController@login');
            Route::get('login/code', 'LoginController@showOtpForm')
                ->name('login.code');
            Route::post('login/code', 'LoginController@otp');
            Route::post('logout', 'LoginController@logout')
                ->name('logout');

            Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')
                ->name('password.request');
            Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')
                ->name('password.email');

            Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')
                ->name('password.reset');
            Route::post('password/reset', 'ResetPasswordController@reset')
                ->name('password.update');
        }
    );

Route::prefix('auth/end-user')
    ->as('auth.end-user.')
    ->namespace('Auth\\EndUser')
    ->group(
        function (): void {
            Route::get('login', 'LoginController@showLoginForm')
                ->name('login');
            Route::post('login', 'LoginController@login');
            Route::post('logout', 'LoginController@logout')
                ->name('logout');

            Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')
                ->name('password.request');
            Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')
                ->name('password.email');

            Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')
                ->name('password.reset');
            Route::post('password/reset', 'ResetPasswordController@reset')
                ->name('password.update');

            Route::get('email/verify', 'VerificationController@show')
                ->name('verification.notice');
            Route::get('email/verify/{id}', 'VerificationController@verify')
                ->name('verification.verify');
            Route::get('email/resend', 'VerificationController@resend')
                ->name('verification.resend');
        }
    );
