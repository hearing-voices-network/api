<?php

namespace App\Docs\Operations\EndUser;

use App\Docs\Schemas\EndUser\EndUserSchema;
use App\Docs\Schemas\PaginationSchema;
use App\Docs\Tags\EndUsersTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class IndexEndUserOperation extends Operation
{
    /**
     * IndexEndUserOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->action = static::ACTION_GET;
        $this->summary = 'List all end users';
        $this->tags = [
            (new EndUsersTag())->name,
        ];
        $this->responses = [
            Response::ok()->content(
                MediaType::json()->schema(
                    new PaginationSchema(
                        new EndUserSchema()
                    )
                )
            ),
        ];
    }
}
