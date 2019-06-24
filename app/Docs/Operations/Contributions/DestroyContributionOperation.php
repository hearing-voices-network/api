<?php

declare(strict_types=1);

namespace App\Docs\Operations\Contributions;

use App\Docs\Responses\ResourceDeletedResponse;
use App\Docs\Tags\ContributionsTag;
use App\Docs\Utils;
use App\Models\Admin;
use App\Models\EndUser;
use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;

class DestroyContributionOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->action(static::ACTION_DELETE)
            ->summary('Delete a specific contribution')
            ->description(
                Utils::operationDescription([Admin::class, EndUser::class])
            )
            ->tags(ContributionsTag::create())
            ->responses(
                ResourceDeletedResponse::create(null, 'contribution')
            );
    }
}
