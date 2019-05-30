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

    /**
     * @param array $data
     * @param \App\Models\Admin $admin
     * @return \App\Models\Admin
     */
    public function update(array $data, Admin $admin): Admin
    {
        $admin->name = $data['name'] ?? $admin->name;
        $admin->phone = $data['phone'] ?? $admin->phone;
        $admin->user->email = $data['email'] ?? $admin->email;
        $admin->user->password = bcrypt($data['password']) ?? $admin->password;

        $admin->save();
        $admin->user->save();

        return $admin;
    }
}
