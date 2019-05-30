<?php

declare(strict_types=1);

namespace App\Docs\Parameters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class SortParameter extends Parameter
{
    /**
     * @param string|null $objectId
     * @param string[] $fields
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter
     */
    public static function create(string $objectId = null, array $fields = []): Parameter
    {
        $fields = '`' . implode($fields, '`,`') . '`';

        return parent::create($objectId)
            ->in(static::IN_QUERY)
            ->name('sort')
            ->description(
                <<<EOT
                Comma separated list of fields to sort by.
                The results are sorted in the order of which the fields have been provided.
                Prefix a field with `-` to indicate a descending sort.
                
                Supported fields: [{$fields}]
                EOT
            )
            ->schema(Schema::string());
    }
}
