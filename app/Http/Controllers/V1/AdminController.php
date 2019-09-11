<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Events\EndpointInvoked;
use App\Http\Controllers\ApiController;
use App\Http\Filters\Admin\EmailFilter;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Resources\AdminResource;
use App\Http\Responses\ResourceDeletedResponse;
use App\Http\Sorts\Admin\EmailSort;
use App\Models\Admin;
use App\Services\AdminService;
use App\Support\Pagination;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Sort;

class AdminController extends ApiController
{
    /**
     * @var \App\Services\AdminService
     */
    protected $adminService;

    /**
     * AdminController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Support\Pagination $pagination
     * @param \App\Services\AdminService $adminService
     */
    public function __construct(
        Request $request,
        Pagination $pagination,
        AdminService $adminService
    ) {
        parent::__construct($request, $pagination);

        $this->middleware(['auth:api', 'verified']);
        $this->authorizeResource(Admin::class);

        $this->adminService = $adminService;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        $baseQuery = Admin::query()
            ->with('user');

        $admins = QueryBuilder::for($baseQuery)
            ->allowedFilters([
                Filter::exact('id'),
                'name',
                'phone',
                Filter::custom('email', EmailFilter::class),
            ])
            ->allowedSorts([
                'name',
                'phone',
                Sort::custom('email', EmailSort::class),
            ])
            ->defaultSort('name')
            ->paginate($this->perPage);

        event(EndpointInvoked::onRead($request, 'Viewed all admins.'));

        return AdminResource::collection($admins);
    }

    /**
     * @param \App\Http\Requests\Admin\StoreAdminRequest $request
     * @throws \Throwable
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(StoreAdminRequest $request): JsonResource
    {
        $admin = DB::transaction(function () use ($request): Admin {
            return $this->adminService->create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => $request->password,
            ]);
        });

        event(EndpointInvoked::onCreate($request, "Created admin [{$admin->id}]."));

        return new AdminResource($admin);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Admin $admin
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function show(Request $request, Admin $admin): JsonResource
    {
        event(EndpointInvoked::onRead($request, "Viewed admin [{$admin->id}]."));

        return new AdminResource($admin);
    }

    /**
     * @param \App\Http\Requests\Admin\UpdateAdminRequest $request
     * @param \App\Models\Admin $admin
     * @throws \Throwable
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function update(UpdateAdminRequest $request, Admin $admin): JsonResource
    {
        $admin = DB::transaction(function () use ($request, $admin): Admin {
            return $this->adminService->update($admin, [
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => $request->password,
            ]);
        });

        event(EndpointInvoked::onUpdate($request, "Updated admin [{$admin->id}]."));

        return new AdminResource($admin);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Admin $admin
     * @return \App\Http\Responses\ResourceDeletedResponse
     */
    public function destroy(Request $request, Admin $admin): ResourceDeletedResponse
    {
        DB::transaction(function () use ($admin): void {
            $this->adminService->delete($admin);
        });

        event(EndpointInvoked::onDelete($request, "Deleted admin [{$admin->id}]."));

        return new ResourceDeletedResponse('admin');
    }
}
