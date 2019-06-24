<?php

declare(strict_types=1);

namespace App\Docs\Operations\Settings;

use App\Docs\Schemas\ResourceSchema;
use App\Docs\Schemas\Setting\SettingsSchema;
use App\Docs\Schemas\Setting\UpdateSettingsSchema;
use App\Docs\Tags\SettingsTag;
use App\Docs\Utils;
use App\Models\Admin;
use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class UpdateSettingsOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->action(static::ACTION_PUT)
            ->summary('Update the settings')
            ->description(
                Utils::operationDescription([Admin::class])
            )
            ->tags(SettingsTag::create())
            ->requestBody(
                RequestBody::create()->content(
                    MediaType::json()->schema(UpdateSettingsSchema::create())
                )
            )
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(
                        ResourceSchema::create(null, SettingsSchema::create())
                    )
                )
            );
    }
}
