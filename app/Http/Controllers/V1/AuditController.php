<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Events\EndpointInvoked;
use App\Http\Controllers\Controller;
use App\Http\Filters\Audit\AdminIdFilter;
use App\Http\Filters\Audit\EndUserIdFilter;
use App\Http\Resources\AuditResource;
use App\Models\Audit;
use App\Support\Pagination;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class AuditController extends Controller
{
    /**
     * AuditController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Support\Pagination $pagination
     */
    public function __construct(Request $request, Pagination $pagination)
    {
        parent::__construct($request, $pagination);

        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(Audit::class);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        $baseQuery = Audit::query()
            ->with('user.admin', 'user.endUser', 'client');

        $audits = QueryBuilder::for($baseQuery)
            ->allowedFilters([
                Filter::custom('admin_id', AdminIdFilter::class),
                Filter::custom('end_user_id', EndUserIdFilter::class),
            ])
            ->allowedSorts([
                'created_at',
            ])
            ->defaultSort('-created_at')
            ->paginate($this->perPage);

        event(EndpointInvoked::onRead($request, 'Viewed all audits.'));

        return AuditResource::collection($audits);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Audit $audit
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function show(Request $request, Audit $audit): JsonResource
    {
        event(EndpointInvoked::onRead($request, "Viewed audit [{$audit->id}]."));

        return new AuditResource($audit);
    }
}
