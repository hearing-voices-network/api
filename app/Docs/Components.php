<?php

declare(strict_types=1);

namespace App\Docs;

use App\Docs\SecuritySchemes\OAuth2SecurityScheme;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Components as BaseComponents;

class Components extends BaseComponents
{
    /**
     * @param string|null $objectId
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Components
     */
    public static function create(string $objectId = null): BaseComponents
    {
        return parent::create($objectId)
            ->securitySchemes(OAuth2SecurityScheme::create());
    }
}
