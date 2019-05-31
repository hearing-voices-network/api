<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Audit;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuditPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list admins.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function list(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the admin.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Audit $audit
     * @return bool
     */
    public function view(User $user, Audit $audit): bool
    {
        return $user->isAdmin();
    }
}
