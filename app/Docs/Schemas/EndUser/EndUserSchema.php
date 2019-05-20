<?php

declare(strict_types=1);

namespace App\Docs\Schemas\EndUser;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class EndUserSchema extends Schema
{
    /**
     * EndUserSchema constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->type = static::TYPE_OBJECT;
        $this->properties = [
            Schema::string('id')->format(static::FORMAT_UUID),
            Schema::string('email'),
            Schema::string('gdpr_consented_at')->format(static::FORMAT_DATE_TIME),
            Schema::string('created_at')->format(static::FORMAT_DATE_TIME),
            Schema::string('updated_at')->format(static::FORMAT_DATE_TIME),
        ];
    }
}
