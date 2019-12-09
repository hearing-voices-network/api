<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\EndUser;

use App\Events\EndpointInvoked;
use App\Http\Controllers\ApiController;
use App\Http\Resources\EndUserResource;
use App\Models\EndUser;
use App\Support\Pagination;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeController extends ApiController
{
    /**
     * MeController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Support\Pagination $pagination
     */
    public function __construct(Request $request, Pagination $pagination)
    {
        parent::__construct($request, $pagination);

        $this->middleware('auth:api');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function __invoke(Request $request): JsonResource
    {
        $this->authorize('me', EndUser::class);

        $endUser = $request->user('api')->endUser;

        event(EndpointInvoked::onRead($request, "Viewed end user [{$endUser->id}]."));

        return new EndUserResource($endUser);
    }
}
