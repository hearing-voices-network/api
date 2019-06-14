<?php

declare(strict_types=1);

use App\Models\EndUser;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Date;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(EndUser::class, function (Faker $faker): array {
    return [
        'user_id' => function (): string {
            return factory(User::class)->create()->id;
        },
        'gdpr_consented_at' => Date::now(),
    ];
});
