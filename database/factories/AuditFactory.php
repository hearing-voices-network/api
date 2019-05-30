<?php

declare(strict_types=1);

use App\Models\Audit;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Audit::class, function (Faker $faker) {
    return [
        'action' => Audit::ACTION_READ,
        'ip_address' => $faker->ipv4,
    ];
});
