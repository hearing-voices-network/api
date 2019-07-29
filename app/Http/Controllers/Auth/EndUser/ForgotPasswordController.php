<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\EndUser;

use App\Http\Controllers\Controller;
use App\Support\Pagination;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * ForgotPasswordController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Support\Pagination $pagination
     */
    public function __construct(Request $request, Pagination $pagination)
    {
        parent::__construct($request, $pagination);

        $this->middleware('guest:web');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showLinkRequestForm(): View
    {
        return view('end-user.auth.forgotten-password');
    }
}
