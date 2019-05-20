<?php

namespace App\Docs\Paths\Admins;

use App\Docs\Operations\Admins\CreateAdminOperation;
use App\Docs\Operations\Admins\ListAdminsOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;

class AdminsRootPath extends PathItem
{
    /**
     * UsersRoot constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->route = '/admins';
        $this->operations = [
            new ListAdminsOperation(),
            new CreateAdminOperation(),
        ];
    }
}
