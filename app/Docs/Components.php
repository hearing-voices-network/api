<?php

namespace App\Docs;

use App\Docs\SecuritySchemes\OAuth2SecurityScheme;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Components as BaseComponents;

class Components extends BaseComponents
{
    /**
     * Components constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->securitySchemes = [
            new OAuth2SecurityScheme(),
        ];
    }
}
