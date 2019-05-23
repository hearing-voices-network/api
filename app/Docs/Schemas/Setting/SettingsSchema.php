<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Setting;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class SettingsSchema extends Schema
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
                Schema::object('frontend_content')->properties(
                    Schema::object('home_page')->properties(
                        // TODO: Fill in from designs.
                        Schema::string('title')
                    )
                ),
                Schema::object('email_content')->properties(
                    Schema::object('admin')->properties(
                        // TODO: Fill in from notification cards.
                        Schema::string('password_reset')
                    ),
                    Schema::object('end_user')->properties(
                        // TODO: Fill in from notification cards.
                        Schema::string('email_confirmation'),
                        Schema::string('password_reset')
                    )
                )
            );
    }
}
