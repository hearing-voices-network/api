<?php

declare(strict_types=1);

namespace App\VariableSubstitution\Email\Admin;

use App\Models\Contribution;
use App\Models\EndUser;
use App\VariableSubstitution\BaseVariableSubstituter;

class NewEndUserSubstituter extends BaseVariableSubstituter
{
    /**
     * @var \App\Models\EndUser
     */
    protected $endUser;

    /**
     * NewEndUserSubstituter constructor.
     *
     * @param \App\Models\EndUser $endUser
     */
    public function __construct(EndUser $endUser)
    {
        $this->endUser = $endUser;
    }

    /**
     * @return array
     */
    protected function variables(): array
    {
        return [
            'END_USER_EMAIL' => $this->endUser->user->email,
            'END_USER_CREATED_AT' => $this->endUser->user->created_at
                ->format(config('connecting_voices.datetime_format')),
        ];
    }
}
