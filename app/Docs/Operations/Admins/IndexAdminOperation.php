<?php

declare(strict_types=1);

namespace App\Docs\Operations\Admins;

use App\Docs\Parameters\FilterParameter;
use App\Docs\Parameters\PageParameter;
use App\Docs\Parameters\PerPageParameter;
use App\Docs\Parameters\SortParameter;
use App\Docs\Schemas\Admin\AdminSchema;
use App\Docs\Schemas\PaginationSchema;
use App\Docs\Tags\AdminsTag;
use App\Docs\Utils;
use App\Models\Admin;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class IndexAdminOperation extends Operation
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
            ->summary('List all admins')
            ->description(
                Utils::operationDescription([Admin::class])
            )
            ->tags(AdminsTag::create())
            ->parameters(
                PageParameter::create(),
                PerPageParameter::create(),
                FilterParameter::create(null, 'name')
                    ->description('The name of the Admin to filter by')
                    ->schema(Schema::string()),
                FilterParameter::create(null, 'email')
                    ->description('The email of the Admin to filter by')
                    ->schema(Schema::string()),
                FilterParameter::create(null, 'phone')
                    ->description('The phone of the Admin to filter by')
                    ->schema(Schema::string()),
                SortParameter::create(null, ['name', 'email', 'phone'])
            )
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(
                        PaginationSchema::create(null, AdminSchema::create())
                    )
                )
            );
    }
}
