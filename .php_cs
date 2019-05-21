<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__)
    ->exclude([
        'bootstrap',
        'docker',
        'public',
        'resources',
        'storage',
        'tests',
        'vendor',
    ]);

return Config::create()
    ->setRules([
        '@PSR2' => true,
        'declare_strict_types' => true,
        'blank_line_after_opening_tag' => true,
    ])
    ->setFinder($finder);
