<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Events\EndpointInvoked;
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(Request $request): ResourceCollection
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

        event(EndpointInvoked::onRead($request, 'Viewed all notifications.'));

        return NotificationResource::collection($notifications);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Notification $notification
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function show(Request $request, Notification $notification): JsonResource
    {
        event(EndpointInvoked::onRead($request, "Viewed notification [{$notification->id}]."));

        return new NotificationResource($notification);
    }
}
