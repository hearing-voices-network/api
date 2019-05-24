<?php

declare(strict_types=1);

namespace App\Docs\Operations\Tags;

use App\Docs\Responses\ResourceDeletedResponse;
use App\Docs\Tags\TagsTag;
use App\Docs\Utils;
use App\Models\Admin;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class DestroyTagOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): Operation
    {
        return parent::create($objectId)
            ->action(static::ACTION_DELETE)
            ->summary('Delete a specific tag')
            ->description(
                Utils::operationDescription([Admin::class])
            )
            ->tags(TagsTag::create())
            ->parameters(
                Parameter::query()->name('type')->required()->schema(
                    Schema::string()->enum('soft_delete', 'force_delete')
                )
            )
            ->responses(
                ResourceDeletedResponse::create(null, 'tag')
            );
    }
}
