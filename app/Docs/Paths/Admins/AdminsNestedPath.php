<?php

declare(strict_types=1);

namespace App\Docs\Paths\Admins;

use App\Docs\Operations\Admins\DestroyAdminOperation;
use App\Docs\Operations\Admins\ShowAdminOperation;
use App\Docs\Operations\Admins\UpdateAdminOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class AdminsNestedPath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->route('/admins/{admin}')
            ->parameters(
                Parameter::path()
                    ->name('admin')
                    ->description('The ID of the admin')
                    ->schema(Schema::string()->format(Schema::FORMAT_UUID))
                    ->required()
            )
            ->operations(
                ShowAdminOperation::create(),
                UpdateAdminOperation::create(),
                DestroyAdminOperation::create()
            );
    }
}
