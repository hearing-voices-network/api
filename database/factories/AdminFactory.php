<?php

declare(strict_types=1);

use App\Models\Admin;
use App\Models\User;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Admin::class, function (Faker $faker): array {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'name' => $faker->firstName,
        'phone' => '07000000000',
    ];
});
