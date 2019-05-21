<?php

declare(strict_types=1);

namespace App\Docs\Operations\Tags;

use App\Docs\Schemas\Tag\StoreTagSchema;
use App\Docs\Schemas\Tag\TagSchema;
use App\Docs\Tags\TagsTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class StoreTagOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): Operation
    {
        return parent::create($objectId)
            ->action(static::ACTION_POST)
            ->summary('Create a tag')
            ->tags(TagsTag::create())
            ->requestBody(
                RequestBody::create()->content(
                    MediaType::json()->schema(StoreTagSchema::create())
                )
            )
            ->responses(
                Response::created()->content(
                    MediaType::json()->schema(TagSchema::create())
                )
            );
    }
}
