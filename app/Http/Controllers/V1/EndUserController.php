<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\EndUser\EmailFilter;
use App\Http\Filters\EndUser\EmailVerifiedFilter;
use App\Http\Filters\NullFilter;
use App\Http\Requests\EndUser\IndexEndUserRequest;
use App\Http\Resources\EndUserResource;
use App\Models\EndUser;
use App\Support\Pagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\QueryBuilder\Filter;
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
                Filter::custom('with_soft_deletes', NullFilter::class),
                )
            ->paginate($this->perPage);

        return EndUserResource::collection($endUsers);
    }
}
