<?php

namespace App\Docs\Operations\Admins;

use App\Docs\Responses\ResourceDeletedResponse;
use App\Docs\Tags\AdminsTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;

class DestroyAdminOperation extends Operation
{
    /**
     * DestroyAdminOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->action = static::ACTION_DELETE;
        $this->summary = 'Delete a specific admin';
        $this->tags = [
            (new AdminsTag())->name,
        ];
        $this->responses = [
            new ResourceDeletedResponse('admin'),
        ];
    }
}
