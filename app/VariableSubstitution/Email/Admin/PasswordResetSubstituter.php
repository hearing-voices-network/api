<?php

declare(strict_types=1);

namespace App\VariableSubstitution\Email\Admin;

use App\Models\Admin;
use App\VariableSubstitution\BaseVariableSubstituter;

class PasswordResetSubstituter extends BaseVariableSubstituter
{
    /**
     * @var \App\Models\Admin
     */
    protected $admin;

    /**
     * @var string
     */
    protected $passwordResetUrl;

    /**
     * PasswordResetSubstituter constructor.
     *
     * @param \App\Models\Admin $admin
     * @param string $passwordResetUrl
     */
    public function __construct(Admin $admin, string $passwordResetUrl)
    {
        $this->admin = $admin;
        $this->passwordResetUrl = $passwordResetUrl;
    }

    /**
     * @return array
     */
    protected function variables(): array
    {
        return [
            'ADMIN_EMAIL' => $this->admin->user->email,
            'ADMIN_NAME' => $this->admin->name,
            'ADMIN_PHONE' => $this->admin->phone,
            'PASSWORD_RESET_URL' => $this->passwordResetUrl,
        ];
    }
}
