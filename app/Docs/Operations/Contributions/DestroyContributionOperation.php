<?php

declare(strict_types=1);

namespace App\Docs\Operations\Contributions;

use App\Docs\Responses\ResourceDeletedResponse;
use App\Docs\Tags\ContributionsTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;

class DestroyContributionOperation extends Operation
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
            ->summary('Delete a specific contribution')
            ->tags(ContributionsTag::create())
            ->responses(
                ResourceDeletedResponse::create(null, 'contribution')
            );
    }
}
