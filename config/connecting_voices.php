<?php

declare(strict_types=1);

return [

    'repo_url' => 'https://github.com/hearing-voices-network/api',

    'age_requirement' => [
        'min' => 13,
        'max' => 100,
    ],

    'pagination' => [
        'default' => 10,
        'max' => 100,
    ],

    'file_tokens' => [
        // 5 minutes.
        'expiry_time' => 60 * 5,
    ],

    'admin_email' => 'info+connecting-voices@ayup.agency', // TODO: Change this once confirmed by client.

];
