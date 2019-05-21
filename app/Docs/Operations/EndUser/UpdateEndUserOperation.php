<?php

declare(strict_types=1);

namespace App\Docs\Operations\EndUser;

use App\Docs\Schemas\EndUser\EndUserSchema;
use App\Docs\Schemas\EndUser\UpdateEndUserSchema;
use App\Docs\Tags\EndUsersTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class UpdateEndUserOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): Operation
    {
        return parent::create($objectId)
            ->action(static::ACTION_PUT)
            ->summary('Update a specific end user')
            ->tags(EndUsersTag::create())
            ->requestBody(
                RequestBody::create()->content(
                    MediaType::json()->schema(UpdateEndUserSchema::create())
                )
            )
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(EndUserSchema::create())
                )
            );
    }
}
