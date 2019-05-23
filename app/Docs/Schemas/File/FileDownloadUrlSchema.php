<?php

declare(strict_types=1);

namespace App\Docs\Schemas\File;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class FileDownloadUrlSchema extends Schema
{
    /**
     * @param string|null $objectId
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     */
    public static function create(string $objectId = null): Schema
    {
        return parent::create($objectId)
            ->type(static::TYPE_OBJECT)
            ->properties(
                Schema::string('download_url'),
                Schema::string('expires_at')
                    ->format(Schema::FORMAT_DATE_TIME)
            );
    }
}
