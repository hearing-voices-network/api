<?php

declare(strict_types=1);

use App\Models\Tag;
use Faker\Generator as Faker;

/* @var $factory \Illuminate\Database\Eloquent\Factory */
$factory->define(Tag::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
    ];
});
