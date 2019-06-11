<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EndUserResource;
use App\Models\EndUser;
use App\Support\Pagination;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;

class EndUserController extends Controller
{
    /**
     * EndUserController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Support\Pagination $pagination
     */
    public function __construct(Request $request, Pagination $pagination)
    {
        parent::__construct($request, $pagination);

        $this->middleware('auth');
        $this->authorizeResource(EndUser::class);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $baseQuery = EndUser::query();

        $endUsers = QueryBuilder::for($baseQuery)
            ->paginate($this->perPage);

        return EndUserResource::collection($endUsers);
    }
}
