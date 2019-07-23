<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Audit;
use App\Services\AuditService;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class AuditServiceTest extends TestCase
{
    /** @test */
    public function it_creates_an_audit_record(): void
    {
        /** @var \App\Services\AuditService $auditService */
        $auditService = resolve(AuditService::class);

        $ipAddress = $this->faker->ipv4;
        $userAgent = $this->faker->userAgent;
        $createdAt = Date::now();

        $audit = $auditService->create([
            'user_id' => null,
            'client_id' => null,
            'action' => Audit::ACTION_CREATE,
            'description' => 'Lorem ipsum',
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'created_at' => $createdAt,
        ]);

        $this->assertDatabaseHas('audits', ['id' => $audit->id]);
        $this->assertEquals(null, $audit->user_id);
        $this->assertEquals(null, $audit->client_id);
        $this->assertEquals(Audit::ACTION_CREATE, $audit->action);
        $this->assertEquals('Lorem ipsum', $audit->description);
        $this->assertEquals($ipAddress, $audit->ip_address);
        $this->assertEquals($userAgent, $audit->user_agent);
        $this->assertEquals($createdAt->toIso8601String(), $audit->created_at->toIso8601String());
    }
}
