<?php

declare(strict_types=1);

namespace App\Docs\Operations\Contributions;

use App\Docs\Schemas\Contribution\ContributionSchema;
use App\Docs\Schemas\ResourceSchema;
use App\Docs\Tags\ContributionsTag;
use App\Docs\Utils;
use App\Models\Admin;
use App\Models\EndUser;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;

class ShowContributionOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @throws \ReflectionException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): Operation
    {
        return parent::create($objectId)
            ->action(static::ACTION_GET)
            ->summary('Get a specific contribution')
            ->description(
                Utils::operationDescription(
                    ['Public', Admin::class, EndUser::class],
                    <<<'EOT'
                    * If accessed by the public, then only public contributions will be accessible.
                    * If access by an end user, then only public contributions and all of their own
                    will be accessible.
                    EOT
                )
            )
            ->tags(ContributionsTag::create())
            ->noSecurity()
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(
                        ResourceSchema::create(null, ContributionSchema::create())
                    )
                )
            );
    }
}
