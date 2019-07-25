<?php

declare(strict_types=1);

namespace App\Events\Audit;

use App\Models\Audit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuditCreated
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var \App\Models\Audit
     */
    protected $audit;

    /**
     * AuditCreated constructor.
     *
     * @param \App\Models\Audit $audit
     */
    public function __construct(Audit $audit)
    {
        $this->audit = $audit;
    }

    /**
     * @return \App\Models\Audit
     */
    public function getAudit(): Audit
    {
        return $this->audit;
    }
}
