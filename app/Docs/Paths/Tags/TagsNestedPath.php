<?php

declare(strict_types=1);

namespace App\Docs\Paths\Tags;

use App\Docs\Operations\Tags\DestroyTagOperation;
use App\Docs\Operations\Tags\ShowTagOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class TagsNestedPath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): PathItem
    {
        return parent::create($objectId)
            ->route('/tags/{tag}')
            ->parameters(
                Parameter::path()
                    ->name('tag')
                    ->description('The ID of the tag')
                    ->schema(Schema::string()->format(Schema::FORMAT_UUID))
                    ->required()
            )
            ->operations(
                ShowTagOperation::create(),
                DestroyTagOperation::create()
            );
    }
}
