<?php

namespace App\Docs\Schemas\Admin;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class CreateAdminSchema extends Schema
{
    /**
     * Admin constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->type = static::TYPE_OBJECT;
        $this->required = [
            'name',
            'phone',
            'email',
            'password',
        ];
        $this->properties = [
            Schema::string('name')->maxLength(255),
            Schema::string('phone')->maxLength(255),
            Schema::string('email')->maxLength(255),
            Schema::string('password')->maxLength(255),
        ];
    }
}
