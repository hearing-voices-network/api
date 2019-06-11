<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

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
                'password' => Hash::make($data['password']),
                'email_verified_at' => Date::now(),
            ])->id,
        ]);
    }

    /**
     * @param \App\Models\Admin $admin
     * @param array $data
     * @return \App\Models\Admin
     */
    public function update(Admin $admin, array $data): Admin
    {
        $admin->update([
            'name' => $data['name'] ?? $admin->name,
            'phone' => $data['phone'] ?? $admin->phone,
        ]);

        $admin->user->update([
            'email' => $data['email'] ?? $admin->user->email,
            'password' => $data['password'] !== null
                ? Hash::make($data['password'])
                : $admin->user->password,
        ]);

        return $admin;
    }

    /**
     * @param \App\Models\Admin $admin
     * @throws \Exception
     */
    public function delete(Admin $admin): void
    {
        /** @var \App\Models\User $user */
        $user = $admin->user;
        $admin->delete();
        $user->audits()->delete();
        $user->notifications()->delete();
        $user->fileToken()->delete();
        $user->forceDelete();
    }
}
