<?php

declare(strict_types=1);

return [

    'age_requirement' => [
        'min' => 13,
        'max' => 100,
    ],

    'pagination' => [
        'default' => 10,
        'max' => 100,
    ],

    'export_download_url' => [
        // 5 minutes.
        'expiry_time' => 60 * 5,
    ],

];
