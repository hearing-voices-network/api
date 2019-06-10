<?php

declare(strict_types=1);

use App\Models\Contribution;
use App\Models\EndUser;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Date;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Contribution::class, function (Faker $faker) {
    return [
        'end_user_id' => function () {
            return factory(EndUser::class)->create()->id;
        },
        'content' => $faker->paragraph,
        'status' => Contribution::STATUS_PUBLIC,
        'status_last_updated_at' => Date::now(),
    ];
});

$factory->state(Contribution::class, Contribution::STATUS_PUBLIC, [
    'status' => Contribution::STATUS_PUBLIC,
]);

$factory->state(Contribution::class, Contribution::STATUS_PRIVATE, [
    'status' => Contribution::STATUS_PRIVATE,
]);

$factory->state(Contribution::class, Contribution::STATUS_IN_REVIEW, [
    'status' => Contribution::STATUS_IN_REVIEW,
]);

$factory->state(Contribution::class, Contribution::STATUS_CHANGES_REQUESTED, function (Faker $faker) {
    return [
        'status' => Contribution::STATUS_CHANGES_REQUESTED,
        'changes_requested' => $faker->paragraph,
    ];
});
