<?php

declare(strict_types=1);

namespace App\Docs\Schemas\Contribution;

use App\Models\Contribution;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class UpdateContributionSchema extends Schema
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
                Schema::string('content')
                    ->maxLength(10000),
                Schema::string('status')
                    ->enum(Contribution::STATUS_IN_REVIEW, Contribution::STATUS_PRIVATE)
                    ->description('Use `in_review` for public consumption and `private` for personal use.'),
                Schema::array('tags')
                    ->items(
                        Schema::object()->properties(
                            Schema::string('id')
                                ->format(Schema::FORMAT_UUID)
                        )
                    )
            );
    }
}
