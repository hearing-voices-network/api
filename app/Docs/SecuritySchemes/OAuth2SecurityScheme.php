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
        return parent::create('OAuth2')
            ->type(static::TYPE_OAUTH2)
            ->description('The standard OAuth2 authentication')
            ->flows(
                OAuthFlow::create()
                    ->flow(OAuthFlow::FLOW_AUTHORIZATION_CODE)
                    ->authorizationUrl(route('passport.authorizations.authorize'))
                    ->tokenUrl(route('passport.token'))
                    ->refreshUrl(route('passport.token'))
            );
    }
}
