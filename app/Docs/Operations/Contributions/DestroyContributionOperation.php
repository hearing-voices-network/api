<?php

declare(strict_types=1);

namespace App\Docs\Operations\Contributions;

use App\Docs\Responses\ResourceDeletedResponse;
use App\Docs\Tags\ContributionsTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;

class DestroyContributionOperation extends Operation
{
    /**
     * DestroyContributionOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->action = static::ACTION_DELETE;
        $this->summary = 'Delete a specific contribution';
        $this->tags = [
            (new ContributionsTag())->name,
        ];
        $this->responses = [
            new ResourceDeletedResponse('contribution'),
        ];
    }
}
