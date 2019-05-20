<?php

namespace App\Docs\Tags;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Tag;

class EndUsersTag extends Tag
{
    /**
     * EndUsersTag constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->name = 'End Users';
    }
}
