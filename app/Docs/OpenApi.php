<?php

declare(strict_types=1);

namespace App\Docs;

use App\Docs\Paths\Admins\AdminsNestedPath;
use App\Docs\Paths\Admins\AdminsRootPath;
use App\Docs\Paths\Contributions\ContributionsRootPath;
use App\Docs\Paths\EndUsers\EndUsersNestedPath;
use App\Docs\Paths\EndUsers\EndUsersRootPath;
use App\Docs\Tags\AdminsTag;
use App\Docs\Tags\EndUsersTag;
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
        $this->servers = [new Server()];
        $this->paths = [
            new AdminsRootPath(),
            new AdminsNestedPath(),
            new EndUsersRootPath(),
            new EndUsersNestedPath(),
            new ContributionsRootPath(),
        ];
        $this->components = new Components();
        $this->tags = [
            new AdminsTag(),
            new EndUsersTag(),
        ];
        $this->externalDocs = new ExternalDocs();
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
