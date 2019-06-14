<?php

declare(strict_types=1);

use App\Models\Tag;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Tag::class, function (Faker $faker): array {
    return [
        'name' => $faker->unique()->word,
    ];
});
