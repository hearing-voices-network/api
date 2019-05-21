<?php

declare(strict_types=1);

namespace App\Docs\Paths\EndUsers;

use App\Docs\Operations\EndUser\IndexEndUserOperation;
use App\Docs\Operations\EndUser\StoreEndUserOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;

class EndUsersRootPath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): PathItem
    {
        return parent::create($objectId)
            ->route('/end-users')
            ->operations(
                IndexEndUserOperation::create(),
                StoreEndUserOperation::create()
            );
    }
}
