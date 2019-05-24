<?php

declare(strict_types=1);

namespace App\Docs\Paths\Files;

use App\Docs\Operations\Files\DownloadFileOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class FilesDownloadPath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): PathItem
    {
        return parent::create($objectId)
            ->route('/files/{file}/download')
            ->parameters(
                Parameter::path()
                    ->name('file')
                    ->description('The ID of the file')
                    ->schema(Schema::string()->format(Schema::FORMAT_UUID))
                    ->required(),
                Parameter::query()
                    ->name('token')
                    ->description('The single use token needed to download private files')
                    ->schema(Schema::string()->format(Schema::FORMAT_UUID))
            )
            ->operations(
                DownloadFileOperation::create()
            );
    }
}
