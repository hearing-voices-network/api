<?php

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

        $this->url = url('/v1'); // TODO: Use route()
        $this->description = 'The API server';
    }
}
