<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list notifications.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function list(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the notification.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Notification $notification
     * @return bool
     */
    public function view(User $user, Notification $notification): bool
    {
        return $user->isAdmin();
    }
}
