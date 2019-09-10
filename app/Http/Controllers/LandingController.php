<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

class LandingController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function __invoke(): View
    {
        return view('landing.index');
    }
}
