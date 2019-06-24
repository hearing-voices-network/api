<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Admin;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list tags.
     *
     * @param \App\Models\User|null $user
     * @return bool
     */
    public function list(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the tag.
     *
     * @param \App\Models\User|null $user
     * @param \App\Models\Admin $admin
     * @return bool
     */
    public function view(?User $user, Admin $admin): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create the tag.
     *
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the tag.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Tag $tag
     * @return bool
     */
    public function delete(User $user, Tag $tag): bool
    {
        return $user->isAdmin();
    }
}
