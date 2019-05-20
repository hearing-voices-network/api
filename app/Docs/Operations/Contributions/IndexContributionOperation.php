<?php

namespace App\Docs\Operations\Contributions;

use App\Docs\Schemas\Contribution\ContributionSchema;
use App\Docs\Schemas\PaginationSchema;
use App\Docs\Tags\ContributionsTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class IndexContributionOperation extends Operation
{
    /**
     * IndexContributionOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->action = static::ACTION_GET;
        $this->summary = 'List all contributions';
        $this->tags = [
            (new ContributionsTag())->name,
        ];
        $this->responses = [
            Response::ok()->content(
                MediaType::json()->schema(
                    new PaginationSchema(
                        new ContributionSchema()
                    )
                )
            ),
        ];
    }
}
