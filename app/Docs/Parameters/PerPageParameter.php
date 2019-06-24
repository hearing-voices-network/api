<?php

declare(strict_types=1);

namespace App\Docs\Parameters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class PerPageParameter extends Parameter
{
    /**
     * @param string|null $objectId
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->in(static::IN_QUERY)
            ->name('per_page')
            ->description('The number of items to load per page')
            ->schema(
                Schema::integer()
                    ->minimum(1)
                    ->maximum((int)config('connecting_voices.pagination.max'))
                    ->default((int)config('connecting_voices.pagination.default'))
            );
    }
}
