<?php

declare(strict_types=1);

namespace App\Docs\Paths\Exports;

use App\Docs\Operations\Export\ShowExportOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class ExportsNestedPath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): PathItem
    {
        return parent::create($objectId)
            ->route('/exports/{export}')
            ->parameters(
                Parameter::path()
                    ->name('export')
                    ->description('The type of export you want')
                    // TODO: Use class constants for this.
                    ->schema(Schema::string()->enum('all'))
                    ->required()
            )
            ->operations(
                ShowExportOperation::create()
            );
    }
}
