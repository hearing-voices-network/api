<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can request the export.
     *
     * @param \App\Models\User $user
     * @param \App\Models\File $file
     * @return bool
     */
    public function request(User $user, File $file): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can download the file.
     *
     * @param \App\Models\User|null $user
     * @param \App\Models\File $file
     * @return bool
     */
    public function download(?User $user, File $file): bool
    {
        if ($file->isPublic()) {
            return true;
        }

        if (optional($user)->isAdmin()) {
            return true;
        }

        return false;
    }
}
