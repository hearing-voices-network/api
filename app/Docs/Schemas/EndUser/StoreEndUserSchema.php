<?php

declare(strict_types=1);

namespace App\Docs\Schemas\EndUser;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class StoreEndUserSchema extends Schema
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     */
    public static function create(string $objectId = null): Schema
    {
        return parent::create($objectId)
            ->type(static::TYPE_OBJECT)
            ->required(
                'email',
                'password'
            )
            ->properties(
                Schema::string('email')
                    ->maxLength(255),
                Schema::string('password')
                    ->maxLength(255),
                Schema::string('country')
                    // TODO: Generate this from countries table
                    ->enum('United Kingdom'),
                Schema::integer('birth_year')
                    ->minimum(today()->year - config('hvn.max_age_requirement'))
                    ->maximum(today()->year - config('hvn.min_age_requirement')),
                Schema::string('gender'),
                Schema::string('ethnicity')
            );
    }
}
