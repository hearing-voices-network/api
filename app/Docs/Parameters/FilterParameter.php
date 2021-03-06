<?php

declare(strict_types=1);

namespace App\Docs\Parameters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;

class FilterParameter extends Parameter
{
    /**
     * @param string|null $objectId
     * @param string $field
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter
     */
    public static function create(string $objectId = null, string $field = ''): BaseObject
    {
        return parent::create($objectId)
            ->in(static::IN_QUERY)
            ->name("filter[{$field}]");
    }
}
