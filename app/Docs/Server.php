<?php

declare(strict_types=1);

namespace App\Docs;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server as BaseServer;

class Server extends BaseServer
{
    /**
     * Server constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // TODO: Use route()
        $this->url = url('/v1');
        $this->description = 'The API server';
    }
}
