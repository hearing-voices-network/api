<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Admin;

use App\Events\EndpointInvoked;
use App\Http\Controllers\ApiController;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
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

        $this->middleware(['auth:api', 'verified']);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function __invoke(Request $request): JsonResource
    {
        $this->authorize('me', Admin::class);

        $admin = $request->user('api')->admin;

        event(EndpointInvoked::onRead($request, "Viewed admin [{$admin->id}]."));

        return new AdminResource($admin);
    }
}
