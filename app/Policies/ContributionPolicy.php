<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Contribution;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContributionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list contributions.
     *
     * @param \App\Models\User|null $user
     * @return bool
     */
    public function list(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the contribution.
     *
     * @param \App\Models\User|null $user
     * @param \App\Models\Contribution $contribution
     * @return bool
     */
    public function view(?User $user, Contribution $contribution): bool
    {
        if (optional($user)->isAdmin()) {
            return true;
        }

        if ($contribution->isPublic()) {
            return true;
        }

        if (optional($user)->isEndUser() && $contribution->belongsToEndUser($user->endUser)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create contributions.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->isEndUser();
    }

    /**
     * Determine whether the user can update the contribution.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Contribution $contribution
     * @return bool
     */
    public function update(User $user, Contribution $contribution): bool
    {
        return $user->isEndUser() && $contribution->belongsToEndUser($user->endUser);
    }

    /**
     * Determine whether the user can delete the contribution.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Contribution $contribution
     * @return bool
     */
    public function delete(User $user, Contribution $contribution): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isEndUser() && $contribution->belongsToEndUser($user->endUser)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can approve the contribution.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Contribution $contribution
     * @return bool
     */
    public function approve(User $user, Contribution $contribution): bool
    {
        return $user->isAdmin() && $contribution->isInReview();
    }

    /**
     * Determine whether the user can reject the contribution.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Contribution $contribution
     * @return bool
     */
    public function reject(User $user, Contribution $contribution): bool
    {
        return $user->isAdmin() && ($contribution->isPublic() || $contribution->isInReview());
    }
}
