<?php

declare(strict_types=1);

namespace App\Models;

use App\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Mutators\UserMutators;
    use Relationships\UserRelationships;
    use Scopes\UserScopes;
    use HasApiTokens;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->admin !== null;
    }

    /**
     * @return bool
     */
    public function isEndUser(): bool
    {
        return $this->endUser !== null;
    }
}
