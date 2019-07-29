<?php

declare(strict_types=1);

use App\Models\Admin;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Date;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Admin::class, function (Faker $faker): array {
    return [
        'user_id' => function () {
            return factory(User::class)->create([
                'email_verified_at' => Date::now(),
            ])->id;
        },
        'name' => $faker->firstName,
        'phone' => '07000000000',
    ];
});
