<?php

declare(strict_types=1);

namespace App\Events\Export;

use App\Models\Export;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExportCreated
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var \App\Models\Export
     */
    protected $export;

    /**
     * ExportCreated constructor.
     *
     * @param \App\Models\Export $export
     */
    public function __construct(Export $export)
    {
        $this->export = $export;
    }

    /**
     * @return \App\Models\Export
     */
    public function getExport(): Export
    {
        return $this->export;
    }
}
