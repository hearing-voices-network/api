<?php

namespace App\Docs\Paths\Admins;

use App\Docs\Operations\Admins\DestroyAdminOperation;
use App\Docs\Operations\Admins\ShowAdminOperation;
use App\Docs\Operations\Admins\UpdateAdminOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class AdminsNestedPath extends PathItem
{
    /**
     * AdminsNestedPath constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->route = '/admins/{admin}';
        $this->parameters = [
            Parameter::path()
                ->name('admin')
                ->description('The ID of the admin')
                ->schema(Schema::string()->format(Schema::FORMAT_UUID))
                ->required(),
        ];
        $this->operations = [
            new ShowAdminOperation(),
            new UpdateAdminOperation(),
            new DestroyAdminOperation(),
        ];
    }
}
