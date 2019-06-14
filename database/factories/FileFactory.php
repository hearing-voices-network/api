<?php

declare(strict_types=1);

use App\Models\File;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(File::class, function (Faker $faker): array {
    return [
        'filename' => Str::random() . '.txt',
        'mime_type' => File::MIME_TYPE_TXT,
        'is_private' => false,
    ];
});

$factory->state(File::class, 'public', [
    'is_private' => false,
]);

$factory->state(File::class, 'private', [
    'is_private' => true,
]);
