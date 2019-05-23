<?php

declare(strict_types=1);

namespace App\Docs\Operations\Export;

use App\Docs\Schemas\File\FileDownloadUrlSchema;
use App\Docs\Tags\ExportsTag;
use App\Docs\Utils;
use App\Models\Admin;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AllOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class RequestExportOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): Operation
    {
        return parent::create($objectId)
            ->action(static::ACTION_POST)
            ->summary('Request a download URL for a specific export')
            ->description(
                Utils::operationDescription(
                    [Admin::class],
                    sprintf(
                        <<<'EOT'
                        This returns a download URL which will expire within %d seconds, and can 
                        only be accessed once.
                        EOT,
                        config('hvn.file_tokens.expiry_time')
                    )
                )
            )
            ->tags(ExportsTag::create())
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(
                        AllOf::create()->schemas(
                            FileDownloadUrlSchema::create(),
                            Schema::object()->properties(
                                Schema::string('decryption_key')
                            )
                        )
                    )
                )
            );
    }
}