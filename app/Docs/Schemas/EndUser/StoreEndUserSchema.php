<?php

namespace App\Docs\Schemas\EndUser;

class StoreEndUserSchema extends UpdateEndUserSchema
{
    /**
     * StoreEndUserSchema constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->required[] = 'password';
    }
}
