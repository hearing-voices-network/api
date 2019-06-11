<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\EndUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EndUserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list end users.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function list(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the end user.
     *
     * @param \App\Models\User $user
     * @param \App\Models\EndUser $endUser
     * @return bool
     */
    public function view(User $user, EndUser $endUser): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isEndUser() && $user->endUser->is($endUser)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create end users.
     *
     * @param \App\Models\User|null $user
     * @return bool
     */
    public function create(?User $user): bool
    {
        return $user->isAdmin() || !$user->isEndUser();
    }

    /**
     * Determine whether the user can update the end user.
     *
     * @param \App\Models\User $user
     * @param \App\Models\EndUser $endUser
     * @return bool
     */
    public function update(User $user, EndUser $endUser): bool
    {
        return $user->isEndUser() && $user->endUser->is($endUser);
    }

    /**
     * Determine whether the user can delete the end user.
     *
     * @param \App\Models\User $user
     * @param \App\Models\EndUser $endUser
     * @return bool
     */
    public function delete(User $user, EndUser $endUser): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isEndUser() && $user->endUser->is($endUser)) {
            return true;
        }

        return false;
    }
}
