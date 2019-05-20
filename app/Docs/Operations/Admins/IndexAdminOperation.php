<?php

declare(strict_types=1);

namespace App\Docs\Operations\Admins;

use App\Docs\Schemas\Admin\AdminSchema;
use App\Docs\Schemas\PaginationSchema;
use App\Docs\Tags\AdminsTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class IndexAdminOperation extends Operation
{
    /**
     * IndexAdminOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->action = static::ACTION_GET;
        $this->summary = 'List all admins';
        $this->tags = [
            (new AdminsTag())->name,
        ];
        $this->responses = [
            Response::ok()->content(
                MediaType::json()->schema(
                    new PaginationSchema(
                        new AdminSchema()
                    )
                )
            ),
        ];
    }
}
