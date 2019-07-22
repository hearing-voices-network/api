<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\Notification\AdminIdFilter;
use App\Http\Filters\Notification\EndUserIdFilter;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Support\Pagination;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class NotificationController extends Controller
{
    /**
     * NotificationController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Support\Pagination $pagination
     */
    public function __construct(Request $request, Pagination $pagination)
    {
        parent::__construct($request, $pagination);

        $this->middleware('auth');
        $this->authorizeResource(Notification::class);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $baseQuery = Notification::query()
            ->with('user.admin', 'user.endUser');

        $notifications = QueryBuilder::for($baseQuery)
            ->allowedFilters([
                Filter::custom('admin_id', AdminIdFilter::class),
                Filter::custom('end_user_id', EndUserIdFilter::class),
            ])
            ->allowedSorts([
                'created_at',
            ])
            ->defaultSort('-created_at')
            ->paginate($this->perPage);

        return NotificationResource::collection($notifications);
    }

    /**
     * @param \App\Models\Notification $notification
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function show(Notification $notification): JsonResource
    {
        return new NotificationResource($notification);
    }
}
