<?php

declare(strict_types=1);

namespace App\Docs\SecuritySchemes;

use GoldSpecDigital\ObjectOrientedOAS\Objects\OAuthFlow;
use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;

class OAuth2SecurityScheme extends SecurityScheme
{
    /**
     * OAuth2 constructor.
     */
    public function __construct()
    {
        parent::__construct('OAuth2');

        $this->type = static::TYPE_OAUTH2;
        $this->description = 'The standard OAuth2 authentication';
        $this->flows = [
            OAuthFlow::create()
                ->flow(OAuthFlow::FLOW_CLIENT_CREDENTIALS)
                ->tokenUrl(url('/oauth/token'))// TODO: Use route()
                ->refreshUrl(url('/oauth/token')), // TODO: Use route()
        ];
    }
}
