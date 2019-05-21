<?php

declare(strict_types=1);

namespace App\Docs\Paths\Contributions;

use App\Docs\Operations\Contributions\ApproveContributionOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class ContributionsApprovePath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): PathItem
    {
        return parent::create($objectId)
            ->route('/contributions/{contribution}/approve')
            ->description('This endpoint can only be invoked if the contribution is awaiting')
            ->parameters(
                Parameter::path()
                    ->name('contribution')
                    ->description('The ID of the contribution')
                    ->schema(Schema::string()->format(Schema::FORMAT_UUID))
                    ->required()
            )
            ->operations(
                ApproveContributionOperation::create()
            );
    }
}
