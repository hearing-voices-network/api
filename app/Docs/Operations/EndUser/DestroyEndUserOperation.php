<?php

namespace App\Docs\Operations\EndUser;

use App\Docs\Responses\ResourceDeletedResponse;
use App\Docs\Tags\EndUsersTag;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class DestroyEndUserOperation extends Operation
{
    /**
     * DestroyEndUserOperation constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->action = static::ACTION_DELETE;
        $this->summary = 'Delete a specific end user';
        $this->tags = [
            (new EndUsersTag())->name,
        ];
        $this->parameters = [
            Parameter::query()->name('type')->required()->schema(
                Schema::string()->enum('soft_delete', 'force_delete')
            ),
        ];
        $this->responses = [
            new ResourceDeletedResponse('end user'),
        ];
    }
}
