<?php

declare(strict_types=1);

namespace App\Docs\Paths\Contributions;

use App\Docs\Operations\Contributions\DestroyContributionOperation;
use App\Docs\Operations\Contributions\ShowContributionOperation;
use App\Docs\Operations\Contributions\UpdateContributionOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class ContributionsNestedPath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): PathItem
    {
        return parent::create($objectId)
            ->route('/contributions/{contribution}')
            ->parameters(
                Parameter::path()
                    ->name('contribution')
                    ->description('The ID of the contribution')
                    ->schema(Schema::string()->format(Schema::FORMAT_UUID))
                    ->required()
            )
            ->operations(
                ShowContributionOperation::create(),
                UpdateContributionOperation::create(),
                DestroyContributionOperation::create()
            );
    }
}
