<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Contribution;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class UpdateContributionSchema extends Schema
{
    /**
     * UpdateContributionSchema constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->type = static::TYPE_OBJECT;
        $this->required = ['content', 'status'];
        $this->properties = [
            Schema::string('content')->maxLength(10000),
            Schema::string('status')->enum('in_review', 'private') // TODO: Use class constants for these values.
                ->description('Use `in_review` for public consumption and `private` for personal use.'),
        ];
    }
}
