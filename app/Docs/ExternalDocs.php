<?php

declare(strict_types=1);

namespace App\Docs;

use GoldSpecDigital\ObjectOrientedOAS\Objects\ExternalDocs as BaseExternalDocs;

class ExternalDocs extends BaseExternalDocs
{
    /**
     * ExternalDocs constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->description = 'The GitHub repo';
        $this->url = 'https://github.com/hearing-voices-network/api';
    }
}
