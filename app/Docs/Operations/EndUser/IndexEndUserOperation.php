<?php

declare(strict_types=1);

namespace App\Docs\Operations\EndUser;

use App\Docs\Schemas\EndUser\EndUserSchema;
use App\Docs\Schemas\PaginationSchema;
use App\Docs\Tags\EndUsersTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class IndexEndUserOperation extends Operation
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
            ->summary('List all end users')
            ->tags(EndUsersTag::create())
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(
                        PaginationSchema::create(null, EndUserSchema::create())
                    )
                )
            );
    }
}
