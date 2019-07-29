<?php

declare(strict_types=1);

namespace App\VariableSubstitution\Email\Admin;

use App\Models\Contribution;
use App\VariableSubstitution\BaseVariableSubstituter;
use Illuminate\Support\Facades\Config;

class ContributionApprovedSubstituter extends BaseVariableSubstituter
{
    /**
     * @var \App\Models\Contribution
     */
    protected $contribution;

    /**
     * ContributionApprovedSubstituter constructor.
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
            'CONTRIBUTION_APPROVED_AT' => $this->contribution->status_last_updated_at
                ->format(Config::get('connecting_voices.datetime_format')),
            'TAGS' => $this->contribution
                ->tags()
                ->pluck('name')
                ->implode(', '),
        ];
    }
}
