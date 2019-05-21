<?php

declare(strict_types=1);

namespace App\Docs;

use App\Docs\Paths\Admins\AdminsNestedPath;
use App\Docs\Paths\Admins\AdminsRootPath;
use App\Docs\Paths\Contributions\ContributionsApprovePath;
use App\Docs\Paths\Contributions\ContributionsNestedPath;
use App\Docs\Paths\Contributions\ContributionsRejectPath;
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
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\OpenApi
     */
    public static function create(string $objectId = null): BaseOpenApi
    {
        return parent::create($objectId)
            ->openapi(static::OPENAPI_3_0_2)
            ->info(Info::create())
            ->servers(Server::create())
            ->paths(
                AdminsRootPath::create(),
                AdminsNestedPath::create(),
                EndUsersRootPath::create(),
                EndUsersNestedPath::create(),
                ContributionsRootPath::create(),
                ContributionsNestedPath::create(),
                ContributionsApprovePath::create(),
                ContributionsRejectPath::create()
            )
            ->components(Components::create())
            ->tags(
                AdminsTag::create(),
                EndUsersTag::create()
            )
            ->externalDocs(ExternalDocs::create());
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
