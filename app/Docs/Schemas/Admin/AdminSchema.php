<?php

namespace App\Docs\Schemas\Admin;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class AdminSchema extends Schema
{
    /**
     * Admin constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->type = static::TYPE_OBJECT;
        $this->properties = [
            Schema::string('id')->format(static::FORMAT_UUID),
            Schema::string('name')->maxLength(255),
            Schema::string('phone')->maxLength(255),
            Schema::string('email')->maxLength(255),
            Schema::string('created_at')->format(static::FORMAT_DATE_TIME),
            Schema::string('updated_at')->format(static::FORMAT_DATE_TIME),
            Schema::string('deleted_at')->format(static::FORMAT_DATE_TIME)->nullable(),
        ];
    }
}
