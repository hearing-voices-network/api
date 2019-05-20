<?php

declare(strict_types=1);

namespace App\Docs\Paths\EndUsers;

use App\Docs\Operations\EndUser\IndexEndUserOperation;
use App\Docs\Operations\EndUser\StoreEndUserOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;

class EndUsersRootPath extends PathItem
{
    /**
     * EndUsersRootPath constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->route = '/end-users';
        $this->operations = [
            new IndexEndUserOperation(),
            new StoreEndUserOperation(),
        ];
    }
}
