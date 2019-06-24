<?php

declare(strict_types=1);

namespace App\Docs\Paths\EndUsers;

use App\Docs\Operations\EndUser\DestroyEndUserOperation;
use App\Docs\Operations\EndUser\ShowEndUserOperation;
use App\Docs\Operations\EndUser\UpdateEndUserOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class EndUsersNestedPath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->route('/end-users/{end_user}')
            ->parameters(
                Parameter::path()
                    ->name('end_user')
                    ->description('The ID of the end user')
                    ->schema(Schema::string()->format(Schema::FORMAT_UUID))
                    ->required()
            )
            ->operations(
                ShowEndUserOperation::create(),
                UpdateEndUserOperation::create(),
                DestroyEndUserOperation::create()
            );
    }
}
