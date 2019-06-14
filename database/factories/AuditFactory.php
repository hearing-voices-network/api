<?php

declare(strict_types=1);

use App\Models\Audit;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Date;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Audit::class, function (Faker $faker): array {
    return [
        'action' => Audit::ACTION_READ,
        'ip_address' => $faker->ipv4,
        'created_at' => Date::now(),
    ];
});
