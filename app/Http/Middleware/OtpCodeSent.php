<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class OtpCodeSent
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if (session()->has('otp.user_id') && session()->has('otp.code')) {
            return $next($request);
        }

        return redirect(route('login'));
    }
}
