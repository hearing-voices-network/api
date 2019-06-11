<?php

declare(strict_types=1);

namespace App\Docs\Operations\Tags;

use App\Docs\Parameters\SortParameter;
use App\Docs\Schemas\Tag\TagSchema;
use App\Docs\Tags\TagsTag;
use App\Docs\Utils;
use App\Models\Admin;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class IndexTagOperation extends Operation
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
            ->summary('List all tags')
            ->description(
                Utils::operationDescription(
                    [Admin::class],
                    'This endpoint does not return a paginated set, but instead all tags at once.'
                )
            )
            ->tags(TagsTag::create())
            ->parameters(
                SortParameter::create(null, [], 'name')
            )
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(
                        Schema::array()->items(TagSchema::create())
                    )
                )
            );
    }
}
