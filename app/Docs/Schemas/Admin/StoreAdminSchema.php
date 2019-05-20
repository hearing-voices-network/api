<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Admin;

class StoreAdminSchema extends UpdateAdminSchema
{
    /**
     * CreateAdminSchema constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->required[] = 'password';
    }
}
