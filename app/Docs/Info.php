<?php

declare(strict_types=1);

namespace App\Docs;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Contact;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Info as BaseInfo;

class Info extends BaseInfo
{
    /**
     * Info constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->title = 'Hearing Voices Network API';
        $this->description = 'Documentation on how to use the API';
        $this->contact = Contact::create()
            ->name('Ayup Digital')
            ->url('https://ayup.agency')
            ->email('info@ayup.agency');
        $this->version = 'v1';
    }
}
