<?php

declare(strict_types=1);

namespace App\Docs\Paths\EndUsers;

use App\Docs\Operations\EndUser\ShowEndUserOperation;
use App\Docs\Utils;
use App\Models\EndUser;
use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;

class EndUsersMePath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->route('/end-users/me')
            ->operations(
                ShowEndUserOperation::create()
                    ->summary('Got the authenticated end user')
                    ->description(
                        Utils::operationDescription([EndUser::class])
                    )
            );
    }
}
