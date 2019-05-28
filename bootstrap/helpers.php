<?php

declare(strict_types=1);

if (!function_exists('db')) {
    /**
     * @return \Illuminate\Database\DatabaseManager
     */
    function db(): Illuminate\Database\DatabaseManager
    {
        return resolve('db');
    }
}
