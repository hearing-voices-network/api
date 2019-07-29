<?php

declare(strict_types=1);

namespace App\VariableSubstitution\Email\EndUser;

use App\Models\EndUser;
use App\VariableSubstitution\BaseVariableSubstituter;

class PasswordResetSubstituter extends BaseVariableSubstituter
{
    /**
     * @var \App\Models\EndUser
     */
    protected $endUser;

    /**
     * @var string
     */
    protected $passwordResetUrl;

    /**
     * PasswordResetSubstituter constructor.
     *
     * @param \App\Models\EndUser $endUser
     * @param string $passwordResetUrl
     */
    public function __construct(EndUser $endUser, string $passwordResetUrl)
    {
        $this->endUser = $endUser;
        $this->passwordResetUrl = $passwordResetUrl;
    }

    /**
     * @return array
     */
    protected function variables(): array
    {
        return [
            'END_USER_EMAIL' => $this->endUser->user->email,
            'PASSWORD_RESET_URL' => $this->passwordResetUrl,
        ];
    }
}
