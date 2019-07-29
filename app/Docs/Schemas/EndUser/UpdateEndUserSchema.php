<?php

declare(strict_types=1);

namespace App\Docs\Schemas\EndUser;

use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;

class UpdateEndUserSchema extends Schema
{
    /**
     * @param string|null $objectId
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->type(static::TYPE_OBJECT)
            ->properties(
                Schema::string('email')
                    ->maxLength(255),
                Schema::string('password')
                    ->maxLength(255),
                Schema::string('country')
                    ->nullable(),
                Schema::integer('birth_year')
                    ->minimum(Date::today()->year - Config::get('connecting_voices.age_requirement.max'))
                    ->maximum(Date::today()->year - Config::get('connecting_voices.age_requirement.min'))
                    ->nullable(),
                Schema::string('gender')
                    ->nullable(),
                Schema::string('ethnicity')
                    ->nullable()
            );
    }
}
