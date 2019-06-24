<?php

declare(strict_types=1);

namespace App\Docs\Operations\Tags;

use App\Docs\Schemas\ResourceSchema;
use App\Docs\Schemas\Tag\TagSchema;
use App\Docs\Tags\TagsTag;
use App\Docs\Utils;
use App\Models\Admin;
use App\Models\EndUser;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class ShowTagOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): Operation
    {
        return parent::create($objectId)
            ->action(static::ACTION_GET)
            ->summary('Get a specific tag')
            ->description(
                Utils::operationDescription(['Public', Admin::class, EndUser::class])
            )
            ->tags(TagsTag::create())
            ->noSecurity()
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(
                        ResourceSchema::create(null, TagSchema::create())
                    )
                )
            );
    }
}
