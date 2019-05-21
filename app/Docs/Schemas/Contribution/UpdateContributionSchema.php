<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Contribution;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class UpdateContributionSchema extends Schema
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     */
    public static function create(string $objectId = null): Schema
    {
        return parent::create($objectId)
            ->type(static::TYPE_OBJECT)
            ->required(
                'content',
                'status'
            )
            ->properties(
                Schema::string('content')->maxLength(10000),
                // TODO: Use class constants for these values.
                Schema::string('status')->enum('in_review', 'private')
                    ->description('Use `in_review` for public consumption and `private` for personal use.')
            );
    }
}