<?php

declare(strict_types=1);

namespace App\Docs\Paths\Files;

use App\Docs\Operations\Files\RequestFileOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class FilesRequestPath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->route('/files/{file}/request')
            ->parameters(
                Parameter::path()
                    ->name('file')
                    ->description('The ID of the file')
                    ->schema(Schema::string()->format(Schema::FORMAT_UUID))
                    ->required()
            )
            ->operations(
                RequestFileOperation::create()
            );
    }
}
