<?php

namespace App\Docs\Operations\Admins;

use App\Docs\Schemas\Admin\AdminSchema;
use App\Docs\Schemas\Admin\UpdateAdminSchema;
use App\Docs\Tags\AdminsTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class UpdateAdminOperation extends Operation
{
    /**
     * UpdateAdminOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->action = static::ACTION_PUT;
        $this->summary = 'Update a specific admin';
        $this->tags = [
            (new AdminsTag())->name,
        ];
        $this->requestBody = RequestBody::create()->content(
            MediaType::json()->schema(new UpdateAdminSchema())
        );
        $this->responses = [
            Response::ok()->content(
                MediaType::json()->schema(new AdminSchema())
            ),
        ];
    }
}
