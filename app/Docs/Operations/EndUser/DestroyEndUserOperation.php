<?php

declare(strict_types=1);

namespace App\Docs\Operations\EndUser;

use App\Docs\Responses\ResourceDeletedResponse;
use App\Docs\Tags\EndUsersTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class DestroyEndUserOperation extends Operation
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
            ->summary('Delete a specific end user')
            ->tags(EndUsersTag::create())
            ->parameters(
                Parameter::query()->name('type')->required()->schema(
                    Schema::string()->enum('soft_delete', 'force_delete')
                )
            )
            ->responses(
                ResourceDeletedResponse::create(null, 'end user')
            );
    }
}
