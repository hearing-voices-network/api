<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;

class AdminController extends Controller
{
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
}
