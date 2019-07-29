<?php

declare(strict_types=1);

namespace App\Docs\Schemas\EndUser;

use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;

class StoreEndUserSchema extends Schema
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     */
    public static function create(string $objectId = null): BaseObject
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
                Schema::string('country'),
                Schema::integer('birth_year')
                    ->minimum(Date::today()->year - Config::get('connecting_voices.age_requirement.max'))
                    ->maximum(Date::today()->year - Config::get('connecting_voices.age_requirement.min')),
                Schema::string('gender'),
                Schema::string('ethnicity')
            );
    }
}
