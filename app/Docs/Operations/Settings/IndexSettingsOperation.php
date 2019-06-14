<?php

declare(strict_types=1);

namespace App\Docs\Operations\Settings;

use App\Docs\Schemas\ResourceSchema;
use App\Docs\Schemas\Setting\SettingsSchema;
use App\Docs\Tags\SettingsTag;
use App\Docs\Utils;
use App\Models\Admin;
use App\Models\EndUser;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class IndexSettingsOperation extends Operation
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
            ->summary('List all settings')
            ->description(
                Utils::operationDescription(
                    ['Public', Admin::class, EndUser::class],
                    'Private settings will only be return for admins.'
                )
            )
            ->tags(SettingsTag::create())
            ->noSecurity()
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(
                        ResourceSchema::create(null, SettingsSchema::create())
                    )
                )
            );
    }
}
