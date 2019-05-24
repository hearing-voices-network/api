<?php

declare(strict_types=1);

namespace App\Docs\Paths\Audits;

use App\Docs\Operations\Audits\IndexAuditOperation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;

class AuditsRootPath extends PathItem
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem
     */
    public static function create(string $objectId = null): PathItem
    {
        return parent::create($objectId)
            ->route('/audits')
            ->operations(
                IndexAuditOperation::create()
            );
    }
}
