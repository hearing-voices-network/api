<?php

declare(strict_types=1);

namespace App\Events\Contribution;

use App\Models\Contribution;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContributionCreated
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var \App\Models\Contribution
     */
    protected $contribution;

    /**
     * ContributionCreated constructor.
     *
     * @param \App\Models\Contribution $contribution
     */
    public function __construct(Contribution $contribution)
    {
        $this->contribution = $contribution;
    }

    /**
     * @return \App\Models\Contribution
     */
    public function getContribution(): Contribution
    {
        return $this->contribution;
    }
}
