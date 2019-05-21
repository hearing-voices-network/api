<?php

declare(strict_types=1);

namespace App\Docs\Operations\Contributions;

use App\Docs\Schemas\Contribution\ContributionSchema;
use App\Docs\Schemas\Contribution\StoreContributionSchema;
use App\Docs\Tags\ContributionsTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class StoreContributionOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): Operation
    {
        return parent::create($objectId)
            ->action(static::ACTION_POST)
            ->summary('Create a contribution')
            ->tags(ContributionsTag::create())
            ->requestBody(
                RequestBody::create()->content(
                    MediaType::json()->schema(StoreContributionSchema::create())
                )
            )
            ->responses(
                Response::created()->content(
                    MediaType::json()->schema(ContributionSchema::create())
                )
            );
    }
}
