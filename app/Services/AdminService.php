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
        $admin->update([
            'name' => $data['name'] ?? $admin->name,
            'phone' => $data['phone'] ?? $admin->phone,
        ]);

        $admin->user()->update([
            'email' => $data['email'] ?? $admin->user->email,
            'password' => $data['password'] !== null
                ? bcrypt($data['password'])
                : $admin->user->password,
        ]);

        return $admin;
    }
}
