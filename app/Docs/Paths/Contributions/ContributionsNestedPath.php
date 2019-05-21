<?php

declare(strict_types=1);

namespace App\Docs\Paths\Contributions;

use App\Docs\Operations\Contributions\DestroyContributionOperation;
use App\Docs\Operations\Contributions\ShowContributionOperation;
use App\Docs\Operations\Contributions\UpdateContributionOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class ContributionsNestedPath extends PathItem
{
    /**
     * ContributionsNestedPath constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->route = '/contributions/{contribution}';
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
