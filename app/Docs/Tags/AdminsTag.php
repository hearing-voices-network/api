<?php

namespace App\Docs\Tags;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Tag;

class AdminsTag extends Tag
{
    /**
     * AdminsTag constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->name = 'Admins';
    }
}
