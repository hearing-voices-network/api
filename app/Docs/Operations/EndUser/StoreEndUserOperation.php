<?php

declare(strict_types=1);

namespace App\Docs\Operations\EndUser;

use App\Docs\Schemas\EndUser\EndUserSchema;
use App\Docs\Schemas\EndUser\StoreEndUserSchema;
use App\Docs\Schemas\ResourceSchema;
use App\Docs\Tags\EndUsersTag;
use App\Docs\Utils;
use App\Models\Admin;
use App\Models\EndUser;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class StoreEndUserOperation extends Operation
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
            ->summary('Create an end user')
            ->description(
                Utils::operationDescription(['Public', Admin::class, EndUser::class])
            )
            ->tags(EndUsersTag::create())
            ->noSecurity()
            ->requestBody(
                RequestBody::create()->content(
                    MediaType::json()->schema(StoreEndUserSchema::create())
                )
            )
            ->responses(
                Response::created()->content(
                    MediaType::json()->schema(
                        ResourceSchema::create(null, EndUserSchema::create())
                    )
                )
            );
    }
}
