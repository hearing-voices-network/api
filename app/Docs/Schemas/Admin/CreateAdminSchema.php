<?php

namespace App\Docs\Schemas\Admin;

class CreateAdminSchema extends UpdateAdminSchema
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
