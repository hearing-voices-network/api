<?php

declare(strict_types=1);

namespace App\Docs\Schemas\EndUser;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class UpdateEndUserSchema extends Schema
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
                Schema::string('email')
                    ->maxLength(255),
                Schema::string('password')
                    ->maxLength(255),
                Schema::string('country')
                    // TODO: Generate this from countries table
                    ->enum('United Kingdom')
                    ->nullable(),
                Schema::integer('birth_year')
                    ->minimum(today()->year - config('connecting_voices.age_requirement.max'))
                    ->maximum(today()->year - config('connecting_voices.age_requirement.min'))
                    ->nullable(),
                Schema::string('gender')
                    ->nullable(),
                Schema::string('ethnicity')
                    ->nullable()
            );
    }
}
