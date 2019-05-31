<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\Audit\AdminIdFilter;
use App\Http\Filters\Audit\EndUserIdFilter;
use App\Http\Resources\AuditResource;
use App\Models\Audit;
use App\Support\Pagination;
use Illuminate\Http\Request;
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

        $this->middleware('auth');
        $this->authorizeResource(Audit::class);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $baseQuery = Audit::query()
            ->orderByDesc('created_at');

        $audits = QueryBuilder::for($baseQuery)
            ->allowedFilters([
                Filter::custom('admin_id', AdminIdFilter::class),
                Filter::custom('end_user_id', EndUserIdFilter::class),
            ])
            ->paginate($this->perPage);

        return AuditResource::collection($audits);
    }
}
