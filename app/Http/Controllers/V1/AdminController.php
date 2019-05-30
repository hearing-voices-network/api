<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use App\Services\AdminService;
use App\Support\Pagination;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;

class AdminController extends Controller
{
    /**
     * AdminController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Support\Pagination $pagination
     */
    public function __construct(Request $request, Pagination $pagination)
    {
        parent::__construct($request, $pagination);

        $this->middleware('auth');
        $this->authorizeResource(Admin::class);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $baseQuery = Admin::query();

        $admins = QueryBuilder::for($baseQuery)
            ->paginate($this->perPage);

        return AdminResource::collection($admins);
    }

    /**
     * @param \App\Http\Requests\Admin\StoreAdminRequest $request
     * @param \App\Services\AdminService $adminService
     * @throws \Throwable
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(StoreAdminRequest $request, AdminService $adminService): JsonResource
    {
        $admin = db()->transaction(function () use ($request, $adminService): Admin {
            return $adminService->create([
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
}
