<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Setting;

use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class SettingsSchema extends Schema
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
                Schema::object('frontend_content')->properties(
                    Schema::object('home_page')->properties(
                        // TODO: Fill in from designs.
                        Schema::string('title')
                    )
                ),
                Schema::object('email_content')->properties(
                    Schema::object('admin')->properties(
                        EmailContentSchema::create('new_contribution'),
                        EmailContentSchema::create('updated_contribution'),
                        EmailContentSchema::create('new_end_user'),
                        EmailContentSchema::create('password_reset')
                    ),
                    Schema::object('end_user')->properties(
                        EmailContentSchema::create('email_confirmation'),
                        EmailContentSchema::create('password_reset'),
                        EmailContentSchema::create('contribution_approved'),
                        EmailContentSchema::create('contribution_rejected')
                    )
                )
            );
    }
}
