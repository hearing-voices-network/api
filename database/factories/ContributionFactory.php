<?php

declare(strict_types=1);

use App\Models\Contribution;
use App\Models\EndUser;
use Faker\Generator as Faker;

/* @var $factory \Illuminate\Database\Eloquent\Factory */
$factory->define(Contribution::class, function (Faker $faker) {
    return [
        'end_user_id' => function () {
            return factory(EndUser::class)->create()->id;
        },
        'content' => $faker->paragraph,
        'status' => Contribution::STATUS_PUBLIC,
    ];
});

$factory->state(Contribution::class, 'private', [
    'status' => Contribution::STATUS_PRIVATE,
]);

$factory->state(Contribution::class, 'inReview', [
    'status' => Contribution::STATUS_IN_REVIEW,
]);

$factory->state(Contribution::class, 'changesRequested', [
    'status' => Contribution::STATUS_CHANGES_REQUESTED,
    'changes_requested' => $faker->paragraph,
]);
