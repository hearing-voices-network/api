<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list settings.
     *
     * @param \App\Models\User|null $user
     * @return bool
     */
    public function list(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the settings.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function update(User $user): bool
    {
        return $user->isAdmin();
    }
}
