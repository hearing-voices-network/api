<?php

declare(strict_types=1);

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker $faker): array {
    return [
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('secret'),
        'remember_token' => Str::random(10),
        'email_verified_at' => now(),
    ];
});
