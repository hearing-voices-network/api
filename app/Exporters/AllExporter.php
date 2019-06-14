<?php

declare(strict_types=1);

namespace App\Exporters;

use Illuminate\Support\Facades\Date;

class AllExporter extends BaseExporter
{
    /**
     * @return string
     */
    protected function filename(): string
    {
        return 'all_export_' . Date::now()->format('Y_m_d') . '.zip';
    }

    /**
     * @return array
     */
    protected function data(): array
    {
        // TODO: Use actual logic for the "all" export.

        return [
            ['Heading 1', 'Heading 2'],
            ['John Doe', 1995],
        ];
    }
}
