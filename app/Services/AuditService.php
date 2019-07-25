<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\Audit\AuditCreated;
use App\Models\Audit;

class AuditService
{
    /**
     * @param array $data
     * @return \App\Models\Audit
     */
    public function create(array $data): Audit
    {
        /** @var \App\Models\Audit $audit */
        $audit = Audit::create([
            'user_id' => $data['user_id'] ?? null,
            'client_id' => $data['client_id'] ?? null,
            'action' => $data['action'],
            'description' => $data['description'] ?? null,
            'ip_address' => $data['ip_address'],
            'user_agent' => $data['user_agent'] ?? null,
            'created_at' => $data['created_at'],
        ]);

        event(new AuditCreated($audit));

        return $audit;
    }
}
