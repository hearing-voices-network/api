<?php

declare(strict_types=1);

namespace App\Docs;

use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\OpenApi as BaseOpenApi;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class OpenApi extends BaseOpenApi implements Responsable
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @throws \ReflectionException
     * @return static
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->openapi(static::OPENAPI_3_0_2)
            ->info(Info::create())
            ->servers(Server::create())
            ->paths(
                Paths\Admins\AdminsRootPath::create(),
                Paths\Admins\AdminsMePath::create(),
                Paths\Admins\AdminsNestedPath::create(),
                Paths\Audits\AuditsRootPath::create(),
                Paths\Audits\AuditsNestedPath::create(),
                Paths\Contributions\ContributionsRootPath::create(),
                Paths\Contributions\ContributionsNestedPath::create(),
                Paths\Contributions\ContributionsApprovePath::create(),
                Paths\Contributions\ContributionsRejectPath::create(),
                Paths\EndUsers\EndUsersMePath::create(),
                Paths\EndUsers\EndUsersRootPath::create(),
                Paths\EndUsers\EndUsersNestedPath::create(),
                Paths\Exports\ExportsRequestPath::create(),
                Paths\Files\FilesRequestPath::create(),
                Paths\Files\FilesDownloadPath::create(),
                Paths\Notifications\NotificationsRootPath::create(),
                Paths\Notifications\NotificationsNestedPath::create(),
                Paths\Settings\SettingsRootPath::create(),
                Paths\Tags\TagsRootPath::create(),
                Paths\Tags\TagsNestedPath::create()
            )
            ->components(Components::create())
            ->security(SecurityRequirement::create())
            ->tags(
                Tags\AdminsTag::create(),
                Tags\AuditsTag::create(),
                Tags\ContributionsTag::create(),
                Tags\EndUsersTag::create(),
                Tags\ExportsTag::create(),
                Tags\FilesTag::create(),
                Tags\NotificationsTag::create(),
                Tags\SettingsTag::create(),
                Tags\TagsTag::create()
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
        return response()->json($this->toArray(), Response::HTTP_OK, [
            'Content-Disposition' => 'inline; filename="openapi.json"',
            'Content-Type' => 'application/json; charset=utf-8',
        ]);
    }
}
