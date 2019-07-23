<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\EndpointInvoked;
use App\Services\AuditService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AuditLogger implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var \App\Services\AuditService
     */
    protected $auditService;

    /**
     * AuditLogger constructor.
     *
     * @param \App\Services\AuditService $auditService
     */
    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\EndpointInvoked $event
     */
    public function handle(EndpointInvoked $event): void
    {
        $this->auditService->create([
            'user_id' => $event->getUser()->id ?? null,
            'client_id' => $event->getClient()->id ?? null,
            'action' => $event->getAction(),
            'description' => $event->getDescription(),
            'ip_address' => $event->getIpAddress(),
            'user_agent' => $event->getUserAgent(),
            'created_at' => $event->getCreatedAt(),
        ]);
    }
}
