<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|void
     */
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            return;
        }

        return $this->isForAdmin($request)
            ? route('auth.admin.login')
            : route('auth.end-user.login');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function isForAdmin(Request $request): bool
    {
        // Check if the request contains an OAuth redirect URI for the admin web app.
        return Str::contains(
            $request->input('redirect_uri', ''),
            Config::get('connecting_voices.admin_url')
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function isForEndUser(Request $request): bool
    {
        // Check if the request contains an OAuth redirect URI for the frontend web app.
        return Str::contains(
            $request->input('redirect_uri', ''),
            Config::get('connecting_voices.frontend_url')
        );
    }
}
