<?php

declare(strict_types=1);

namespace App\Docs\Paths\Contributions;

use App\Docs\Operations\Contributions\RejectContributionOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class ContributionsRejectPath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @throws \ReflectionException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->route('/contributions/{contribution}/reject')
            ->parameters(
                Parameter::path()
                    ->name('contribution')
                    ->description('The ID of the contribution')
                    ->schema(Schema::string()->format(Schema::FORMAT_UUID))
                    ->required()
            )
            ->operations(
                RejectContributionOperation::create()
            );
    }
}
