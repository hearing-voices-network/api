<?php

namespace App\Docs\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class ResourceDeletedResponse extends Response
{
    /**
     * ResourceDeletedResponse constructor.
     *
     * @param string $resource
     */
    public function __construct(string $resource)
    {
        parent::__construct();

        $this->statusCode = 200;
        $this->description = 'OK';
        $this->content = [
            MediaType::json()->schema(
                Schema::object()->properties(
                    Schema::string('message')->example("The $resource has been deleted.")
                )
            )
        ];
    }
}
