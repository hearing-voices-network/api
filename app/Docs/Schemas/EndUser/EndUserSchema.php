<?php

declare(strict_types=1);

namespace App\Docs\Schemas\EndUser;

use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class EndUserSchema extends Schema
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
                Schema::string('id')
                    ->format(static::FORMAT_UUID),
                Schema::string('email'),
                Schema::string('country')
                    ->nullable(),
                Schema::integer('birth_year')
                    ->nullable(),
                Schema::string('gender')
                    ->nullable(),
                Schema::string('ethnicity')
                    ->nullable(),
                Schema::integer('contributions_count'),
                Schema::integer('public_contributions_count'),
                Schema::integer('private_contributions_count'),
                Schema::integer('in_review_contributions_count'),
                Schema::integer('changes_requested_contributions_count'),
                Schema::string('gdpr_consented_at')
                    ->format(static::FORMAT_DATE_TIME),
                Schema::string('email_verified_at')
                    ->format(static::FORMAT_DATE_TIME)
                    ->nullable(),
                Schema::string('created_at')
                    ->format(static::FORMAT_DATE_TIME),
                Schema::string('updated_at')
                    ->format(static::FORMAT_DATE_TIME),
                Schema::string('deleted_at')
                    ->format(static::FORMAT_DATE_TIME)
            );
    }
}
