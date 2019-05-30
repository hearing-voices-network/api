<?php

declare(strict_types=1);

namespace App\Docs\Operations\Admins;

use App\Docs\Responses\ResourceDeletedResponse;
use App\Docs\Tags\AdminsTag;
use App\Docs\Utils;
use App\Models\Admin;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;

class DestroyAdminOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): Operation
    {
        return parent::create($objectId)
            ->action(static::ACTION_DELETE)
            ->summary('Delete a specific admin')
            ->description(
                Utils::operationDescription([Admin::class])
            )
            ->tags(AdminsTag::create())
            ->responses(
                ResourceDeletedResponse::create(null, 'admin')
            );
    }
}
