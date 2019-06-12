<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EndUser;
use App\Models\User;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EndUserService
{
    /**
     * @param array $data
     * @return \App\Models\EndUser
     */
    public function create(array $data): EndUser
    {
        return EndUser::create([
            'user_id' => User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ])->id,
            'country' => $data['country'] ?? null,
            'birth_year' => $data['birth_year'] !== null ? (int)$data['birth_year'] : null,
            'gender' => $data['gender'] ?? null,
            'ethnicity' => $data['ethnicity'] ?? null,
            'gdpr_consented_at' => Date::now(),
        ]);
    }

    /**
     * @param \App\Models\EndUser $endUser
     * @param array $data
     * @return \App\Models\EndUser
     */
    public function update(EndUser $endUser, array $data): EndUser
    {
        $endUser->update([
            'country' => $data['country'] ?? $endUser->country,
            'birth_year' => $data['birth_year'] ?? $endUser->birth_year,
            'gender' => $data['gender'] ?? $endUser->gender,
            'ethnicity' => $data['ethnicity'] ?? $endUser->ethnicity,
        ]);

        $endUser->user->update([
            'email' => $data['email'] ?? $endUser->user->email,
            'password' => $data['password'] !== null
                ? Hash::make($data['password'])
                : $endUser->user->password,
        ]);

        return $endUser;
    }

    /**
     * @param \App\Models\EndUser $endUser
     * @throws \Exception
     * @return \App\Models\EndUser
     */
    public function softDelete(EndUser $endUser): EndUser
    {
        $endUser->user->delete();

        return $endUser;
    }

    /**
     * @param \App\Models\EndUser $endUser
     * @throws \Exception
     */
    public function forceDelete(EndUser $endUser): void
    {
        DB::table('contribution_tag')
            ->whereIn('contribution_id', $endUser->contributions()->pluck('id'))
            ->delete();
        $endUser->contributions()->delete();
        /** @var \App\Models\User $user */
        $user = $endUser->user;
        $user->audits()->delete();
        $user->notifications()->delete();
        $endUser->delete();
        $user->forceDelete();
    }
}
