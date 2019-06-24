<?php

declare(strict_types=1);

namespace App\Docs\Paths\Settings;

use App\Docs\Operations\Settings\IndexSettingsOperation;
use App\Docs\Operations\Settings\UpdateSettingsOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;

class SettingsRootPath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->route('/settings')
            ->operations(
                IndexSettingsOperation::create(),
                UpdateSettingsOperation::create()
            );
    }
}
