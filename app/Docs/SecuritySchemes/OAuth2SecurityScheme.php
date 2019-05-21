<?php

declare(strict_types=1);

namespace App\Docs\SecuritySchemes;

use GoldSpecDigital\ObjectOrientedOAS\Objects\OAuthFlow;
use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;

class OAuth2SecurityScheme extends SecurityScheme
{
    /**
     * @param string|null $objectId
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme
     */
    public static function create(string $objectId = null): SecurityScheme
    {
        return parent::create($objectId)
            ->type(static::TYPE_OAUTH2)
            ->description('The standard OAuth2 authentication')
            ->flows(
                OAuthFlow::create()
                    ->flow(OAuthFlow::FLOW_CLIENT_CREDENTIALS)
                    // TODO: Use route()
                    ->tokenUrl(url('/oauth/token'))
                    // TODO: Use route()
                    ->refreshUrl(url('/oauth/token'))
            );
    }
}
