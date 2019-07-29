<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Events\EndpointInvoked;
use App\Http\Controllers\Controller;
use App\Http\Filters\EndUser\EmailFilter;
use App\Http\Filters\EndUser\EmailVerifiedFilter;
use App\Http\Filters\NullFilter;
use App\Http\Requests\EndUser\DestroyEndUserRequest;
use App\Http\Requests\EndUser\IndexEndUserRequest;
use App\Http\Requests\EndUser\StoreEndUserRequest;
use App\Http\Requests\EndUser\UpdateEndUserRequest;
use App\Http\Resources\EndUserResource;
use App\Http\Responses\ResourceDeletedResponse;
use App\Http\Sorts\EndUser\EmailSort;
use App\Models\EndUser;
use App\Services\EndUserService;
use App\Support\Pagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Sort;

class EndUserController extends Controller
{
    /**
     * @var \App\Services\EndUserService
     */
    protected $endUserService;

    /**
     * EndUserController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Support\Pagination $pagination
     * @param \App\Services\EndUserService $endUserService
     */
    public function __construct(
        Request $request,
        Pagination $pagination,
        EndUserService $endUserService
    ) {
        parent::__construct($request, $pagination);

        $this->middleware(['auth:api', 'verified'])->except('store');
        $this->authorizeResource(EndUser::class);

        $this->endUserService = $endUserService;
    }

    /**
     * @param \App\Http\Requests\EndUser\IndexEndUserRequest $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(IndexEndUserRequest $request): ResourceCollection
    {
        $baseQuery = EndUser::query()
            ->with('user')
            // When soft deleted users aren't included, then only get end users with an active user.
            ->when(
                $request->doesntHaveFilter('with_soft_deletes', 'true'),
                function (Builder $query) use ($request): void {
                    $query->whereHas('user', function (Builder $query): void {
                        $query->whereNull('users.deleted_at');
                    });
                }
            );

        $endUsers = QueryBuilder::for($baseQuery)
            ->allowedFilters(
                Filter::custom('email', EmailFilter::class),
                Filter::custom('email_verified', EmailVerifiedFilter::class),
                Filter::custom('with_soft_deletes', NullFilter::class)
            )
            ->allowedSorts([
                Sort::custom('email', EmailSort::class),
            ])
            ->defaultSort(
                Sort::custom('email', EmailSort::class)
            )
            ->paginate($this->perPage);

        event(EndpointInvoked::onRead($request, 'Viewed all end users.'));

        return EndUserResource::collection($endUsers);
    }

    /**
     * @param \App\Http\Requests\EndUser\StoreEndUserRequest $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(StoreEndUserRequest $request): JsonResource
    {
        $endUser = DB::transaction(function () use ($request): EndUser {
            return $this->endUserService->create([
                'email' => $request->email,
                'password' => $request->password,
                'country' => $request->country,
                'birth_year' => $request->birth_year,
                'gender' => $request->gender,
                'ethnicity' => $request->ethnicity,
            ]);
        });

        event(EndpointInvoked::onCreate($request, "Created end user [{$endUser->id}]."));

        return new EndUserResource($endUser);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\EndUser $endUser
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function show(Request $request, EndUser $endUser): JsonResource
    {
        event(EndpointInvoked::onRead($request, "Viewed end user [{$endUser->id}]."));

        return new EndUserResource($endUser);
    }

    /**
     * @param \App\Http\Requests\EndUser\UpdateEndUserRequest $request
     * @param \App\Models\EndUser $endUser
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function update(UpdateEndUserRequest $request, EndUser $endUser): JsonResource
    {
        $endUser = DB::transaction(function () use ($request, $endUser): EndUser {
            return $this->endUserService->update($endUser, [
                'email' => $request->email,
                'password' => $request->password,
                'country' => $request->country,
                'birth_year' => $request->birth_year,
                'gender' => $request->gender,
                'ethnicity' => $request->ethnicity,
            ]);
        });

        event(EndpointInvoked::onUpdate($request, "Updated end user [{$endUser->id}]."));

        return new EndUserResource($endUser);
    }

    /**
     * @param \App\Http\Requests\EndUser\DestroyEndUserRequest $request
     * @param \App\Models\EndUser $endUser
     * @return \App\Http\Responses\ResourceDeletedResponse
     */
    public function destroy(
        DestroyEndUserRequest $request,
        EndUser $endUser
    ): ResourceDeletedResponse {
        DB::transaction(function () use ($request, $endUser): void {
            $request->type === DestroyEndUserRequest::TYPE_FORCE_DELETE
                ? $this->endUserService->forceDelete($endUser)
                : $this->endUserService->softDelete($endUser);
        });

        $request->type === DestroyEndUserRequest::TYPE_FORCE_DELETE
            ? event(EndpointInvoked::onDelete($request, "Force deleted end user [{$endUser->id}]."))
            : event(EndpointInvoked::onDelete($request, "Soft deleted end user [{$endUser->id}]."));

        return new ResourceDeletedResponse('end user');
    }
}
