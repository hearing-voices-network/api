<?php

declare(strict_types=1);

namespace App\Docs\Paths\Contributions;

use App\Docs\Operations\Contributions\IndexContributionOperation;
use App\Docs\Operations\Contributions\StoreContributionOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;

class ContributionsRootPath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): PathItem
    {
        return parent::create($objectId)
            ->route('/contributions')
            ->operations(
                IndexContributionOperation::create(),
                StoreContributionOperation::create()
            );
    }
}
