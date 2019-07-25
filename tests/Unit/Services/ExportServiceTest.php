<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Events\Export\ExportRequested;
use App\Exceptions\ExporterNotFoundException;
use App\Exceptions\InvalidExporterException;
use App\Models\Admin;
use App\Models\Export;
use App\Services\ExportService;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ExportServiceTest extends TestCase
{
    /** @test */
    public function it_creates_an_export_for_a_valid_export(): void
    {
        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        /** @var \App\Services\ExportService $exportService */
        $exportService = resolve(ExportService::class);

        $export = $exportService->create('all', $admin);

        $this->assertInstanceOf(Export::class, $export);
    }

    /** @test */
    public function it_throws_exception_for_missing_exporter(): void
    {
        $this->expectException(ExporterNotFoundException::class);

        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        /** @var \App\Services\ExportService $exportService */
        $exportService = resolve(ExportService::class);

        $exportService->create('missing', $admin);
    }

    /** @test */
    public function it_throws_exception_for_invalid_exporter(): void
    {
        $this->expectException(InvalidExporterException::class);

        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        /** @var \App\Services\ExportService $exportService */
        $exportService = resolve(ExportService::class);

        $exportService->create('test', $admin, 'Tests\\Stubs\\Exporters');
    }

    /** @test */
    public function it_dispatches_an_event_when_requested(): void
    {
        Event::fake([ExportRequested::class]);

        /** @var \App\Models\Admin $admin */
        $admin = factory(Admin::class)->create();

        /** @var \App\Services\ExportService $exportService */
        $exportService = resolve(ExportService::class);

        $export = $exportService->create('all', $admin);

        Event::assertDispatched(
            ExportRequested::class,
            function (ExportRequested $event) use ($export): bool {
                return $event->getExport()->is($export);
            }
        );
    }
}
