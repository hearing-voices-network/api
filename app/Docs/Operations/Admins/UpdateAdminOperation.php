<?php

declare(strict_types=1);

namespace App\Docs\Operations\Admins;

use App\Docs\Schemas\Admin\AdminSchema;
use App\Docs\Schemas\Admin\UpdateAdminSchema;
use App\Docs\Tags\AdminsTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class UpdateAdminOperation extends Operation
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
            ->summary('Update a specific admin')
            ->tags(AdminsTag::create())
            ->requestBody(
                RequestBody::create()->content(
                    MediaType::json()->schema(UpdateAdminSchema::create())
                )
            )
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(AdminSchema::create())
                )
            );
    }
}
