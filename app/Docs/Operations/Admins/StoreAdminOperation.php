<?php

declare(strict_types=1);

namespace App\Docs\Operations\Admins;

use App\Docs\Schemas\Admin\AdminSchema;
use App\Docs\Schemas\Admin\StoreAdminSchema;
use App\Docs\Schemas\ResourceSchema;
use App\Docs\Tags\AdminsTag;
use App\Docs\Utils;
use App\Models\Admin;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class StoreAdminOperation extends Operation
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
            ->summary('Create an admin')
            ->description(
                Utils::operationDescription([Admin::class])
            )
            ->tags(AdminsTag::create())
            ->requestBody(
                RequestBody::create()->content(
                    MediaType::json()->schema(StoreAdminSchema::create())
                )
            )
            ->responses(
                Response::created()->content(
                    MediaType::json()->schema(
                        ResourceSchema::create(null, AdminSchema::create())
                    )
                )
            );
    }
}
