<?php

declare(strict_types=1);

namespace App\Models\Mutators;

use App\Models\Admin;
use App\Models\EndUser;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserMutators
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function endUser(): HasOne
    {
        return $this->hasOne(EndUser::class);
    }
}
