<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Contribution;

use App\Docs\Schemas\Tag\TagSchema;
use App\Models\Contribution;
use App\Support\Enum;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class ContributionSchema extends Schema
{
    /**
     * @param string|null $objectId
     * @throws \ReflectionException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     */
    public static function create(string $objectId = null): Schema
    {
        return parent::create($objectId)
            ->type(static::TYPE_OBJECT)
            ->properties(
                Schema::string('id')
                    ->format(static::FORMAT_UUID),
                Schema::string('end_user_id')
                    ->format(static::FORMAT_UUID)
                    ->description('This is only provided when the requesting user is an admin or the same end user.'),
                Schema::string('content'),
                Schema::string('excerpt'),
                Schema::string('status')
                    ->enum(...(new Enum(Contribution::class))->getValues('STATUS')),
                Schema::string('changes_requested')
                    ->description('This is only provided when the requesting user is an admin or the same end user.')
                    ->nullable(),
                Schema::string('status_last_updated_at')
                    ->format(static::FORMAT_DATE_TIME)
                    ->description('This is only provided when the requesting user is an admin or the same end user.'),
                Schema::string('created_at')
                    ->format(static::FORMAT_DATE_TIME),
                Schema::string('updated_at')
                    ->format(static::FORMAT_DATE_TIME),
                Schema::array('tags')
                    ->items(TagSchema::create())
            );
    }
}
