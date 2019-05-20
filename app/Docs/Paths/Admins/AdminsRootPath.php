<?php

namespace App\Docs\Paths\Admins;

use App\Docs\Operations\Admins\StoreAdminOperation;
use App\Docs\Operations\Admins\IndexAdminOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;

class AdminsRootPath extends PathItem
{
    /**
     * AdminsRootPath constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->route = '/admins';
        $this->operations = [
            new IndexAdminOperation(),
            new StoreAdminOperation(),
        ];
    }
}
