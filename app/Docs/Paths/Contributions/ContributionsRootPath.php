<?php

declare(strict_types=1);

namespace App\Docs\Paths\Contributions;

use App\Docs\Operations\Contributions\IndexContributionOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;

class ContributionsRootPath extends PathItem
{
    /**
     * ContributionsRootPath constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->route = '/contributions';
        $this->operations = [
            new IndexContributionOperation(),
        ];
    }
}
