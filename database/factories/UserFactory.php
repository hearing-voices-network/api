<?php

declare(strict_types=1);

use App\Models\User;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker $faker): array {
    return [
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('secret'),
    ];
});

$factory->state(User::class, 'emailVerified', [
    'email_verified_at' => now(),
]);
