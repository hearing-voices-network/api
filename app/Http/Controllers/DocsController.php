<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Docs\OpenApi;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

class DocsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        return view('docs.index');
    }

    /**
     * @throws \ReflectionException
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function openApi(): Responsable
    {
        return OpenApi::create();
    }
}
