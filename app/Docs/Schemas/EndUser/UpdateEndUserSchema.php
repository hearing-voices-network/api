<?php

declare(strict_types=1);

namespace App\Docs\Schemas\EndUser;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class UpdateEndUserSchema extends Schema
{
    /**
     * UpdateEndUserSchema constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->type = static::TYPE_OBJECT;
        $this->required = [
            'email',
        ];
        $this->properties = [
            Schema::string('email')->maxLength(255),
            Schema::string('password')->maxLength(255),
        ];
    }
}
