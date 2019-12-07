<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Pagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

abstract class ApiController extends BaseController
{
    use AuthorizesRequests {
        AuthorizesRequests::resourceAbilityMap as baseResourceAbilityMap;
    }
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @var int
     */
    protected $perPage;

    /**
     * Controller constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Support\Pagination $pagination
     */
    public function __construct(Request $request, Pagination $pagination)
    {
        $this->perPage = $pagination->perPage(
            $this->getPerPage($request)
        );

        Auth::shouldUse('api');
    }

    /**
     * Overridden to add the index method to the map.
     *
     * @return array
     */
    protected function resourceAbilityMap(): array
    {
        return array_merge([
            'list' => 'list',
            'index' => 'list',
        ], $this->baseResourceAbilityMap());
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return int
     */
    protected function getPerPage(Request $request): int
    {
        return (int)$request->input(
            'per_page',
            Config::get('connecting_voices.pagination.default')
        );
    }
}
