<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\WebController;
use App\Models\User;
use App\Sms\GenericSms;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends WebController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest:web')->except('logout');
        $this->middleware('otp')->only('showOtpForm', 'otp');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    /**
     * The user has been authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse|null
     */
    protected function authenticated(Request $request, User $user): ?RedirectResponse
    {
        // If OTP is disabled then skip this method.
        if (!Config::get('connecting_voices.otp_enabled')) {
            return null;
        }

        // Log user out.
        $this->guard()->logout();

        // Place the user ID in the session.
        session()->put('otp.user_id', $user->id);

        // Generate and send the OTP code.
        $otpCode = mt_rand(10000, 99999);
        session()->put('otp.code', $otpCode);
        $this->dispatchNow(
            new GenericSms($user->admin->phone, "{$otpCode} is your authentication code.")
        );

        // Forward the user to the code page.
        return redirect(route('auth.admin.login.code'));
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function showOtpForm(): View
    {
        return view('admin.auth.one-time-password');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function otp(Request $request): RedirectResponse
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            // Throw an exception and exit the method.
            $this->sendOtpLockoutResponse($request);
        }

        // Validate the OTP code and login if correct.
        if ($request->code == $request->session()->get('otp.code')) {
            $userId = $request->session()->get('otp.user_id');
            $this->guard()->login(User::findOrFail($userId));

            $request->session()->regenerate();

            $this->clearLoginAttempts($request);

            session()->forget(['otp.user_id', 'otp.code']);

            return redirect()->intended($this->redirectPath());
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        $this->sendFailedOtpResponse($request);
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param \Illuminate\Http\Request $request
     */
    protected function sendOtpLockoutResponse(Request $request): void
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            'code' => [Lang::get('auth.throttle', ['seconds' => $seconds])],
        ])->status(429);
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request $request
     */
    protected function sendFailedOtpResponse(Request $request): void
    {
        throw ValidationException::withMessages([
            'code' => ['The code provided is incorrect.'],
        ]);
    }

    /**
     * Get the throttle key for the given request.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function throttleKey(Request $request): string
    {
        $key = session()->get(
            'otp.user_id',
            Str::lower($request->input($this->username()))
        );

        return $key . '|' . $request->ip();
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('web');
    }
}
