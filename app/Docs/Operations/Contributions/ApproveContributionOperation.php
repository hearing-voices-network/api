<?php

declare(strict_types=1);

namespace App\Docs\Operations\Contributions;

use App\Docs\Schemas\Contribution\ContributionSchema;
use App\Docs\Tags\ContributionsTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class ApproveContributionOperation extends Operation
{
    /**
     * ApproveContributionOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->action = static::ACTION_PUT;
        $this->summary = 'Approve a specific contribution';
        $this->tags = [
            (new ContributionsTag())->name,
        ];
        $this->responses = [
            Response::ok()->content(
                MediaType::json()->schema(new ContributionSchema())
            ),
        ];
    }
}
