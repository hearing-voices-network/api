<?php

declare(strict_types=1);

namespace App\Docs\Tags;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Tag;

class ContributionsTag extends Tag
{
    /**
     * ContributionsTag constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->name = 'Contributions';
    }
}
