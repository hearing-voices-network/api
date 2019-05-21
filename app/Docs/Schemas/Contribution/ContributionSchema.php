<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Contribution;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class ContributionSchema extends Schema
{
    /**
     * ContributionSchema constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->type = static::TYPE_OBJECT;
        $this->properties = [
            Schema::string('end_user_id')->format(static::FORMAT_UUID)
                ->description('This is only provided when the requesting user is an admin or the same end user.'),
            Schema::string('content'),
            Schema::string('status')
                // TODO: Use class constants for these.
                ->enum('public', 'private', 'in_review', 'changes_requested'),
            Schema::string('changes_requested')
                ->description('This is only provided when the requesting user is an admin or the same end user.'),
            Schema::string('status_last_updated_at')->format(static::FORMAT_DATE_TIME)
                ->description('This is only provided when the requesting user is an admin or the same end user.'),
            Schema::string('created_at')->format(static::FORMAT_DATE_TIME),
            Schema::string('updated_at')->format(static::FORMAT_DATE_TIME),
        ];
    }
}
