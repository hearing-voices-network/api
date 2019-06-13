<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can request the export.
     *
     * @param \App\Models\User $user
     * @param string $export
     * @return bool
     */
    public function request(User $user, string $export): bool
    {
        return $user->isAdmin();
    }
}
