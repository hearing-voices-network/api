<?php

namespace App\Docs\Operations\EndUser;

use App\Docs\Schemas\EndUser\EndUserSchema;
use App\Docs\Schemas\EndUser\StoreEndUserSchema;
use App\Docs\Tags\EndUsersTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class StoreEndUserOperation extends Operation
{
    /**
     * StoreEndUserOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->action = static::ACTION_POST;
        $this->summary = 'Create an end user';
        $this->tags = [
            (new EndUsersTag())->name,
        ];
        $this->requestBody = RequestBody::create()->content(
            MediaType::json()->schema(new StoreEndUserSchema())
        );
        $this->responses = [
            Response::created()->content(
                MediaType::json()->schema(new EndUserSchema())
            ),
        ];
    }
}
