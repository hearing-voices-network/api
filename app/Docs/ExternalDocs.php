<?php

declare(strict_types=1);

namespace App\Docs;

use GoldSpecDigital\ObjectOrientedOAS\Objects\ExternalDocs as BaseExternalDocs;

class ExternalDocs extends BaseExternalDocs
{
    /**
     * @param string|null $objectId
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\ExternalDocs
     */
    public static function create(string $objectId = null): BaseExternalDocs
    {
        return parent::create($objectId)
            ->description('The GitHub repo')
            ->url((string)config('hvn.repo_url'));
    }
}
