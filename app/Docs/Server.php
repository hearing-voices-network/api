<?php

declare(strict_types=1);

namespace App\Docs;

use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Server as BaseServer;

class Server extends BaseServer
{
    /**
     * @param string|null $objectId
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Server
     */
    public static function create(string $objectId = null): BaseObject
    {
        return parent::create($objectId)
            ->url(url('/v1'))
            ->description('The API server');
    }
}
