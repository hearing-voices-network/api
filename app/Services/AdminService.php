<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Admin;
use App\Models\User;

class AdminService
{
    /**
     * @param array $data
     * @return \App\Models\Admin
     */
    public function create(array $data): Admin
    {
        return Admin::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'user_id' => User::create([
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'email_verified_at' => now(),
            ])->id,
        ]);
    }
}
