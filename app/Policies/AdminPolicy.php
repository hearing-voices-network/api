<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
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
     * @param \App\Models\Admin $admin
     * @return bool
     */
    public function view(User $user, Admin $admin): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create admins.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the admin.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Admin $admin
     * @return bool
     */
    public function update(User $user, Admin $admin): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the admin.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Admin $admin
     * @return bool
     */
    public function delete(User $user, Admin $admin): bool
    {
        return $user->isAdmin();
    }
}
