<?php

declare(strict_types=1);

use App\Models\Admin;
use App\Models\User;
use Faker\Generator as Faker;

/* @var $factory \Illuminate\Database\Eloquent\Factory */
$factory->define(Admin::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'name' => $faker->firstName,
        'phone' => '07000000000',
    ];
});
