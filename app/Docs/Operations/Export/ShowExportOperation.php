<?php

declare(strict_types=1);

namespace App\Docs\Operations\Export;

use App\Docs\Tags\ExportsTag;
use App\Docs\Utils;
use App\Models\Admin;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class ShowExportOperation extends Operation
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
            ->summary('Download a specific export')
            ->description(
                Utils::operationDescription(
                    [Admin::class],
                    sprintf(
                        <<<'EOT'
                        This returns a download URL which will expire within %d seconds, and can 
                        only be accessed once.
                        EOT,
                        config('hvn.export_download_url.expiry_time')
                    )
                )
            )
            ->tags(ExportsTag::create())
            ->responses(
                Response::ok()->content(
                    MediaType::json()->schema(
                        Schema::object()->properties(
                            Schema::string('download_url'),
                            Schema::string('expires_at')
                                ->format(Schema::FORMAT_DATE_TIME)
                        )
                    )
                )
            );
    }
}
