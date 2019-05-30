<?php

declare(strict_types=1);

namespace App\Docs\Operations\Contributions;

use App\Docs\Parameters\FilterParameter;
use App\Docs\Parameters\PageParameter;
use App\Docs\Parameters\PerPageParameter;
use App\Docs\Schemas\Contribution\ContributionSchema;
use App\Docs\Schemas\PaginationSchema;
use App\Docs\Tags\ContributionsTag;
use App\Docs\Utils;
use App\Models\Admin;
use App\Models\EndUser;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class IndexContributionOperation extends Operation
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
            ->summary('List all contributions')
            ->description(
                Utils::operationDescription(['Public', Admin::class, EndUser::class])
            )
            ->tags(ContributionsTag::create())
            ->noSecurity()
            ->parameters(
                PageParameter::create(),
                PerPageParameter::create(),
                FilterParameter::create(null, 'end_user_id')
                    ->description(
                        <<<'EOT'
                        The ID of an end user to filter by

                        * Only usable by an admin
                        EOT
                    )
                    ->schema(Schema::string()->format(Schema::FORMAT_UUID)),
                FilterParameter::create(null, 'tag_ids')
                    ->description(
                        <<<'EOT'
                        A comma separated list of tag IDs to filter by
                        
                        * Use `untagged` to search for contributions that have no tag (ignores soft 
                        deleted tags)
                        EOT
                    )
                    ->schema(Schema::string())
            )
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(
                        PaginationSchema::create(null, ContributionSchema::create())
                    )
                )
            );
    }
}
