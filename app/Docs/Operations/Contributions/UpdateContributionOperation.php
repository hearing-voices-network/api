<?php

declare(strict_types=1);

namespace App\Docs\Operations\Contributions;

use App\Docs\Schemas\Contribution\ContributionSchema;
use App\Docs\Schemas\Contribution\UpdateContributionSchema;
use App\Docs\Tags\ContributionsTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class UpdateContributionOperation extends Operation
{
    /**
     * UpdateContributionOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->action = static::ACTION_PUT;
        $this->summary = 'Update a specific contribution';
        $this->tags = [
            (new ContributionsTag())->name,
        ];
        $this->requestBody = RequestBody::create()->content(
            MediaType::json()->schema(new UpdateContributionSchema())
        );
        $this->responses = [
            Response::ok()->content(
                MediaType::json()->schema(new ContributionSchema())
            ),
        ];
    }
}
