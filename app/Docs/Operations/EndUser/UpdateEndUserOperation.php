<?php

declare(strict_types=1);

namespace App\Docs\Operations\EndUser;

use App\Docs\Schemas\EndUser\EndUserSchema;
use App\Docs\Schemas\EndUser\UpdateEndUserSchema;
use App\Docs\Tags\EndUsersTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class UpdateEndUserOperation extends Operation
{
    /**
     * UpdateEndUserOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->action = static::ACTION_PUT;
        $this->summary = 'Update a specific end user';
        $this->tags = [
            (new EndUsersTag())->name,
        ];
        $this->requestBody = RequestBody::create()->content(
            MediaType::json()->schema(new UpdateEndUserSchema())
        );
        $this->responses = [
            Response::ok()->content(
                MediaType::json()->schema(new EndUserSchema())
            ),
        ];
    }
}
