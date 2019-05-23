<?php

declare(strict_types=1);

namespace App\Docs\Operations\Files;

use App\Docs\Tags\FilesTag;
use App\Docs\Utils;
use App\Models\Admin;
use App\Models\EndUser;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class DownloadFileOperation extends Operation
{
    /**
     * @param string|null $objectId
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation
     */
    public static function create(string $objectId = null): Operation
    {
        return parent::create($objectId)
            ->action(static::ACTION_GET)
            ->summary('Download a specific file')
            ->description(
                Utils::operationDescription(
                    ['Public', Admin::class, EndUser::class],
                    <<<'EOT'
                    * Public files are accessible from the public
                    * Private files require the `token` parameter which must be requested
                    EOT
                )
            )
            ->tags(FilesTag::create())
            ->noSecurity()
            ->responses(
                Response::ok()->content(
                    MediaType::pdf()->schema(Schema::string()->format(Schema::FORMAT_BINARY)),
                    MediaType::jpeg()->schema(Schema::string()->format(Schema::FORMAT_BINARY)),
                    MediaType::png()->schema(Schema::string()->format(Schema::FORMAT_BINARY)),
                    MediaType::create()->mediaType('application/zip')->schema(
                        Schema::string()->format(Schema::FORMAT_BINARY)
                    )
                )
            );
    }
}
