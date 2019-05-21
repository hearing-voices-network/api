<?php

declare(strict_types=1);

namespace App\Docs\Paths\Contributions;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class ContributionsApprovePath extends PathItem
{
    /**
     * ContributionsApprovePath constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->route = '/contributions/{contribution}/approve';
        $this->parameters = [
            Parameter::path()
                ->name('contribution')
                ->description('The ID of the contribution')
                ->schema(Schema::string()->format(Schema::FORMAT_UUID))
                ->required(),
        ];
        $this->operations = [
            new ShowContributionOperation(),
            new UpdateContributionOperation(),
            new DestroyContributionOperation(),
        ];
    }
}
