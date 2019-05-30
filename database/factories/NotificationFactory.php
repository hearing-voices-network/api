<?php

declare(strict_types=1);

use App\Models\Notification;
use Faker\Generator as Faker;

/* @var $factory \Illuminate\Database\Eloquent\Factory */
$factory->define(Notification::class, function (Faker $faker) {
    return [
        'channel' => Notification::CHANNEL_EMAIL,
        'recipient' => $faker->safeEmail,
        'content' => $faker->sentence,
    ];
});
