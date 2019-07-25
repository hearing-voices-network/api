<?php

declare(strict_types=1);

namespace App\VariableSubstitution\Email\Admin;

use App\Models\Contribution;
use App\VariableSubstitution\BaseVariableSubstituter;

class NewContributionSubstituter extends BaseVariableSubstituter
{
    /**
     * @var \App\Models\Contribution
     */
    protected $contribution;

    /**
     * NewContribution constructor.
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
            'TEST' => 'value', // TODO
        ];
    }
}
