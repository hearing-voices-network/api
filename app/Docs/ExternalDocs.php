<?php

declare(strict_types=1);

namespace App\Docs;

use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\ExternalDocs as BaseExternalDocs;
use Illuminate\Support\Facades\Config;

class ExternalDocs extends BaseExternalDocs
{
    /**
     * @param string|null $objectId
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\ExternalDocs
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->description('The GitHub repo')
            ->url(Config::get('connecting_voices.repo_url'));
    }
}
