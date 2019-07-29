<?php

declare(strict_types=1);

namespace App\VariableSubstitution\Email\EndUser;

use App\Models\EndUser;
use App\VariableSubstitution\BaseVariableSubstituter;

class EmailConfirmationSubstituter extends BaseVariableSubstituter
{
    /**
     * @var \App\Models\EndUser
     */
    protected $endUser;

    /**
     * @var string
     */
    protected $verifyEmailUrl;

    /**
     * EmailConfirmationSubstituter constructor.
     *
     * @param \App\Models\EndUser $endUser
     * @param string $verifyEmailUrl
     */
    public function __construct(EndUser $endUser, string $verifyEmailUrl)
    {
        $this->endUser = $endUser;
        $this->verifyEmailUrl = $verifyEmailUrl;
    }

    /**
     * @return array
     */
    protected function variables(): array
    {
        return [
            'END_USER_EMAIL' => $this->endUser->user->email,
            'VERIFY_EMAIL_URL' => $this->verifyEmailUrl,
        ];
    }
}
