<?php

declare(strict_types=1);

namespace App\Docs\Operations\EndUser;

use App\Docs\Parameters\FilterParameter;
use App\Docs\Parameters\PageParameter;
use App\Docs\Parameters\PerPageParameter;
use App\Docs\Parameters\SortParameter;
use App\Docs\Schemas\EndUser\EndUserSchema;
use App\Docs\Schemas\PaginationSchema;
use App\Docs\Tags\EndUsersTag;
use App\Docs\Utils;
use App\Models\Admin;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class IndexEndUserOperation extends Operation
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
            ->summary('List all end users')
            ->description(
                Utils::operationDescription([Admin::class])
            )
            ->tags(EndUsersTag::create())
            ->parameters(
                PageParameter::create(),
                PerPageParameter::create(),
                FilterParameter::create(null, 'email')
                    ->description('The email of the End User to filter by')
                    ->schema(Schema::string()),
                FilterParameter::create(null, 'email_verified')
                    ->description('The email verification status of the End User to filter by')
                    ->schema(
                        Schema::string()
                            ->enum('true', 'false', 'all')
                            ->default('all')
                    ),
                FilterParameter::create(null, 'with_soft_deletes')
                    ->description('The soft deletion status of the End User to filter by')
                    ->schema(
                        Schema::string()
                            ->enum('true', 'false')
                            ->default('false')
                    ),
                SortParameter::create(null, ['email'], 'email')
            )
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(
                        PaginationSchema::create(null, EndUserSchema::create())
                    )
                )
            );
    }
}
