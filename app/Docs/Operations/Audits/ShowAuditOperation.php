<?php

declare(strict_types=1);

namespace App\Docs\Operations\Audits;

use App\Docs\Schemas\Audit\AuditSchema;
use App\Docs\Tags\AuditsTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class ShowAuditOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): Operation
    {
        return parent::create($objectId)
            ->action(static::ACTION_GET)
            ->summary('Get a specific audit')
            ->tags(AuditsTag::create())
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(AuditSchema::create())
                )
            );
    }
}
