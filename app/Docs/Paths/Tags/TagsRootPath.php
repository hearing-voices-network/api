<?php

declare(strict_types=1);

namespace App\Docs\Paths\Tags;

use App\Docs\Operations\Tags\IndexTagOperation;
use App\Docs\Operations\Tags\StoreTagOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;

class TagsRootPath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): PathItem
    {
        return parent::create($objectId)
            ->route('/tags')
            ->operations(
                IndexTagOperation::create(),
                StoreTagOperation::create()
            );
    }
}
