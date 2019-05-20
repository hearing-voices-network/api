<?php

declare(strict_types=1);

namespace App\Docs\Operations\EndUser;

use App\Docs\Schemas\EndUser\EndUserSchema;
use App\Docs\Tags\EndUsersTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class ShowEndUserOperation extends Operation
{
    /**
     * ShowEndUserOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->action = static::ACTION_GET;
        $this->summary = 'Get a specific end user';
        $this->tags = [
            (new EndUsersTag())->name,
        ];
        $this->responses = [
            Response::ok()->content(
                MediaType::json()->schema(new EndUserSchema())
            ),
        ];
    }
}
