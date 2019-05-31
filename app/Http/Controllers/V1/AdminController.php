<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
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
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Sort;

class AdminController extends Controller
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
    public function __construct(Request $request, Pagination $pagination, AdminService $adminService)
    {
        parent::__construct($request, $pagination);

        $this->middleware('auth');
        $this->authorizeResource(Admin::class);

        $this->adminService = $adminService;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $baseQuery = Admin::query();

        $admins = QueryBuilder::for($baseQuery)
            ->allowedFilters([
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

        return AdminResource::collection($admins);
    }

    /**
     * @param \App\Http\Requests\Admin\StoreAdminRequest $request
     * @throws \Throwable
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(StoreAdminRequest $request): JsonResource
    {
        $admin = db()->transaction(function () use ($request): Admin {
            return $this->adminService->create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => $request->password,
            ]);
        });

        return new AdminResource($admin);
    }

    /**
     * @param \App\Models\Admin $admin
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function show(Admin $admin): JsonResource
    {
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
        $admin = db()->transaction(function () use ($request, $admin) {
            return $this->adminService->update($admin, [
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => $request->password,
            ]);
        });

        return new AdminResource($admin);
    }

    /**
     * @param \App\Models\Admin $admin
     * @throws \Throwable
     * @return \App\Http\Responses\ResourceDeletedResponse
     */
    public function destroy(Admin $admin): ResourceDeletedResponse
    {
        db()->transaction(function () use ($admin) {
            $this->adminService->delete($admin);
        });

        return new ResourceDeletedResponse('admin');
    }
}
