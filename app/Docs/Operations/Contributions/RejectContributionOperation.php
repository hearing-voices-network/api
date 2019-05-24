<?php

declare(strict_types=1);

namespace App\Docs\Operations\Contributions;

use App\Docs\Schemas\Contribution\ContributionSchema;
use App\Docs\Tags\ContributionsTag;
use App\Docs\Utils;
use App\Models\Admin;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class RejectContributionOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): Operation
    {
        return parent::create($objectId)
            ->action(static::ACTION_PUT)
            ->summary('Reject a specific contribution')
            ->description(
                Utils::operationDescription(
                    [Admin::class],
                    'This endpoint can only be invoked if the contribution is in review.'
                )
            )
            ->tags(ContributionsTag::create())
            ->requestBody(
                RequestBody::create()->required()->content(
                    MediaType::json()->schema(
                        Schema::object()
                            ->required('changes_requested')
                            ->properties(
                                Schema::string('changes_requested')
                            )
                    )
                )
            )
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(ContributionSchema::create())
                )
            );
    }
}
