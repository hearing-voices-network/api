<?php

declare(strict_types=1);

namespace App\VariableSubstitution\Email\Admin;

use App\Models\Contribution;
use App\VariableSubstitution\BaseVariableSubstituter;

class ContributionRejectedSubstituter extends BaseVariableSubstituter
{
    /**
     * @var \App\Models\Contribution
     */
    protected $contribution;

    /**
     * ContributionRejectedSubstituter constructor.
     *
     * @param \App\Models\Contribution $contribution
     */
    public function __construct(Contribution $contribution)
    {
        $this->contribution = $contribution;
    }

    /**
     * @return array
     */
    protected function variables(): array
    {
        return [
            'END_USER_EMAIL' => $this->contribution->endUser->user->email,
            'CONTRIBUTION_CONTENT' => $this->contribution->content,
            'CONTRIBUTION_CHANGES_REQUESTED' => $this->contribution->changes_requested,
            'CONTRIBUTION_REJECTED_AT' => $this->contribution->status_last_updated_at
                ->format(config('connecting_voices.datetime_format')),
            'TAGS' => $this->contribution
                ->tags()
                ->pluck('name')
                ->implode(', '),
        ];
    }
}
