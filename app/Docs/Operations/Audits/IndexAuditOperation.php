<?php

declare(strict_types=1);

namespace App\Docs\Operations\Audits;

use App\Docs\Parameters\FilterParameter;
use App\Docs\Parameters\PageParameter;
use App\Docs\Parameters\PerPageParameter;
use App\Docs\Schemas\Audit\AuditSchema;
use App\Docs\Schemas\PaginationSchema;
use App\Docs\Tags\AuditsTag;
use App\Docs\Utils;
use App\Models\Admin;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class IndexAuditOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @throws \ReflectionException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): Operation
    {
        return parent::create($objectId)
            ->action(static::ACTION_GET)
            ->summary('List all audits')
            ->description(
                Utils::operationDescription(
                    [Admin::class],
                    'Audits are returned in descending order of the `created_at` field'
                )
            )
            ->tags(AuditsTag::create())
            ->parameters(
                PageParameter::create(),
                PerPageParameter::create(),
                FilterParameter::create(null, 'admin_id')
                    ->description('The ID of an admin to filter by')
                    ->schema(Schema::string()->format(Schema::FORMAT_UUID)),
                FilterParameter::create(null, 'end_user_id')
                    ->description('The ID of an end user to filter by')
                    ->schema(Schema::string()->format(Schema::FORMAT_UUID))
            )
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(
                        PaginationSchema::create(null, AuditSchema::create())
                    )
                )
            );
    }
}
