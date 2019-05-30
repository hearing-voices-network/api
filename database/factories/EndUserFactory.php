<?php

declare(strict_types=1);

use App\Models\EndUser;
use App\Models\User;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(EndUser::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'gdpr_consented_at' => now(),
    ];
});
