<?php

declare(strict_types=1);

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker $faker): array {
    return [
        'email' => $faker->unique()->safeEmail,
        'password' => Hash::make('secret'),
    ];
});

$factory->state(User::class, 'emailVerified', [
    'email_verified_at' => Date::now(),
]);
