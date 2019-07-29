<?php

declare(strict_types=1);

namespace App\Docs;

use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Contact;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Info as BaseInfo;
use Illuminate\Support\Facades\Config;

class Info extends BaseInfo
{
    /**
     * @param string|null $objectId
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Info
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->title(Config::get('app.name') . ' API')
            ->description('Documentation on how to use the API')
            ->contact(
                Contact::create()
                    ->name(Config::get('ayup.name'))
                    ->url(Config::get('ayup.url'))
                    ->email(Config::get('ayup.email'))
            )
            ->version('v1');
    }
}
