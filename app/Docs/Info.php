<?php

namespace App\Docs;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Info as BaseInfo;

class Info extends BaseInfo
{
    /**
     * Info constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->title = 'Hearing Voices Network API';
        $this->version = 'v1';
    }
}
