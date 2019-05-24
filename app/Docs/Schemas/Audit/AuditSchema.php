<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Audit;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class AuditSchema extends Schema
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
                Schema::string('id')
                    ->format(static::FORMAT_UUID),
                Schema::string('user_id')
                    ->format(static::FORMAT_UUID)
                    ->nullable(),
                Schema::integer('oauth_client_id')
                    ->nullable(),
                Schema::string('action')
                    // TODO: Use class constants for these.
                    ->enum('create', 'read', 'update', 'delete', 'login', 'logout'),
                Schema::string('description')
                    ->nullable(),
                Schema::string('ip_address'),
                Schema::string('user_agent')
                    ->nullable(),
                Schema::string('created_at')
                    ->format(static::FORMAT_DATE_TIME)
            );
    }
}
