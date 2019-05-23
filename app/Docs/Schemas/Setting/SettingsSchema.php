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
                        Schema::object('new_contribution')->properties(
                            Schema::string('subject'),
                            Schema::string('body')
                        ),
                        Schema::object('updated_contribution')->properties(
                            Schema::string('subject'),
                            Schema::string('body')
                        ),
                        Schema::object('new_end_user')->properties(
                            Schema::string('subject'),
                            Schema::string('body')
                        ),
                        Schema::object('password_reset')->properties(
                            Schema::string('subject'),
                            Schema::string('body')
                        )
                    ),
                    Schema::object('end_user')->properties(
                        Schema::object('email_confirmation')->properties(
                            Schema::string('subject'),
                            Schema::string('body')
                        ),
                        Schema::object('password_reset')->properties(
                            Schema::string('subject'),
                            Schema::string('body')
                        ),
                        Schema::object('contribution_approved')->properties(
                            Schema::string('subject'),
                            Schema::string('body')
                        ),
                        Schema::object('contribution_rejected')->properties(
                            Schema::string('subject'),
                            Schema::string('body')
                        )
                    )
                )
            );
    }
}
