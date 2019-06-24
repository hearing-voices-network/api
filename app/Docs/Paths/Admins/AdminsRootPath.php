<?php

declare(strict_types=1);

namespace App\Docs\Paths\Admins;

use App\Docs\Operations\Admins\IndexAdminOperation;
use App\Docs\Operations\Admins\StoreAdminOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;

class AdminsRootPath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->route('/admins')
            ->operations(
                IndexAdminOperation::create(),
                StoreAdminOperation::create()
            );
    }
}
