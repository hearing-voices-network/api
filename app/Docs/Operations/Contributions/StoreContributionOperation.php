<?php

declare(strict_types=1);

namespace App\Docs\Operations\Contributions;

use App\Docs\Schemas\Contribution\ContributionSchema;
use App\Docs\Schemas\Contribution\StoreContributionSchema;
use App\Docs\Schemas\PaginationSchema;
use App\Docs\Tags\ContributionsTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class StoreContributionOperation extends Operation
{
    /**
     * StoreContributionOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->action = static::ACTION_POST;
        $this->summary = 'Create a contribution';
        $this->tags = [
            (new ContributionsTag())->name,
        ];
        $this->requestBody = RequestBody::create()->content(
            MediaType::json()->schema(new StoreContributionSchema())
        );
        $this->responses = [
            Response::created()->content(
                MediaType::json()->schema(new ContributionSchema())
            ),
        ];
    }
}
