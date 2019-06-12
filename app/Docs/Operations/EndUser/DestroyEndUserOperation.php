<?php

declare(strict_types=1);

namespace App\Docs\Operations\EndUser;

use App\Docs\Responses\ResourceDeletedResponse;
use App\Docs\Tags\EndUsersTag;
use App\Docs\Utils;
use App\Models\Admin;
use App\Models\EndUser;
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
            ->description(
                Utils::operationDescription(
                    [Admin::class, EndUser::class],
                    <<<'EOT'
                    * If an end user is making the request, then they can only delete their own
                    end user resource.
                    EOT
                )
            )
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
