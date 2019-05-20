<?php

namespace App\Docs\Operations\Admins;

use App\Docs\Schemas\Admin\AdminSchema;
use App\Docs\Schemas\Admin\CreateAdminSchema;
use App\Docs\Tags\AdminsTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class CreateAdminOperation extends Operation
{
    /**
     * CreateAdminOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->action = static::ACTION_POST;
        $this->summary = 'Create an admin';
        $this->tags = [
            (new AdminsTag())->name,
        ];
        $this->requestBody = RequestBody::create()->content(
            MediaType::json()->schema(new CreateAdminSchema())
        );
        $this->responses = [
            Response::created()->content(
                MediaType::json()->schema(new AdminSchema())
            ),
        ];
    }
}
