<?php

declare(strict_types=1);

namespace App\Docs\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class ResourceDeletedResponse extends Response
{
    /**
     * @param string|null $objectId
     * @param string|null $resource
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Response
     */
    public static function create(string $objectId = null, string $resource = null): Response
    {
        return parent::create($objectId)
            ->statusCode(200)
            ->description('OK')
            ->content(
                MediaType::json()->schema(
                    Schema::object()->properties(
                        Schema::string('message')
                            ->example("The $resource has been deleted.")
                    )
                )
            );
    }
}
