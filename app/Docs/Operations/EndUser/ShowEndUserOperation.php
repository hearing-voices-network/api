<?php

declare(strict_types=1);

namespace App\Docs\Operations\EndUser;

use App\Docs\Schemas\EndUser\EndUserSchema;
use App\Docs\Schemas\ResourceSchema;
use App\Docs\Tags\EndUsersTag;
use App\Docs\Utils;
use App\Models\Admin;
use App\Models\EndUser;
use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class ShowEndUserOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->action(static::ACTION_GET)
            ->summary('Get a specific end user')
            ->description(
                Utils::operationDescription(
                    [Admin::class, EndUser::class],
                    <<<'EOT'
                    * If an end user is making the request, then they can only access their own
                    end user resource.
                    EOT
                )
            )
            ->tags(EndUsersTag::create())
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(
                        ResourceSchema::create(null, EndUserSchema::create())
                    )
                )
            );
    }
}
