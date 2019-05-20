<?php

namespace App\Docs;

use App\Docs\Paths\Admins\AdminsRootPath;
use App\Docs\Tags\AdminsTag;
use GoldSpecDigital\ObjectOrientedOAS\OpenApi as BaseOpenApi;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class OpenApi extends BaseOpenApi implements Responsable
{
    /**
     * OpenApiBuilder constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->openapi = static::OPENAPI_3_0_2;
        $this->info = new Info();
        $this->paths = [
            new AdminsRootPath(),
        ];
        $this->tags = [
            new AdminsTag(),
        ];
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json($this->toArray());
    }
}
