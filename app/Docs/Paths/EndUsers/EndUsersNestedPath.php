<?php

declare(strict_types=1);

namespace App\Docs\Paths\EndUsers;

use App\Docs\Operations\EndUser\DestroyEndUserOperation;
use App\Docs\Operations\EndUser\ShowEndUserOperation;
use App\Docs\Operations\EndUser\UpdateEndUserOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class EndUsersNestedPath extends PathItem
{
    /**
     * EndUsersNestedPath constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->route = '/end-users/{end_user}';
        $this->parameters = [
            Parameter::path()
                ->name('end_user')
                ->description('The ID of the end user')
                ->schema(Schema::string()->format(Schema::FORMAT_UUID))
                ->required(),
        ];
        $this->operations = [
            new ShowEndUserOperation(),
            new UpdateEndUserOperation(),
            new DestroyEndUserOperation(),
        ];
    }
}
