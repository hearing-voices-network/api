<?php

declare(strict_types=1);

namespace App\Docs;

use App\Models\Admin;
use App\Models\EndUser;
use GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException;

class Utils
{
    /**
     * Utils constructor.
     */
    protected function __construct()
    {
        // Prevent instantiation.
    }

    /**
     * @param array $accessibleBy
     * @param string|null $description
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @return string
     */
    public static function operationDescription(
        array $accessibleBy,
        string $description = null
    ): string {
        // Only allow an array of strings.
        foreach ($accessibleBy as $accessor) {
            if (is_string($accessor)) {
                continue;
            }

            throw new InvalidArgumentException();
        }

        // Format the accessible by into markdown.
        $accessibleBy = collect($accessibleBy)
            ->map(function (string $accessor) {
                // If class names passed in then convert to meaningful names.
                switch ($accessor) {
                    case Admin::class:
                        $accessor = 'Admins';
                        break;
                    case EndUser::class:
                        $accessor = 'End users';
                        break;
                }

                return "* `{$accessor}`";
            })
            ->implode(PHP_EOL);

        // Prepare the required markdown string.
        $markdown = <<<EOT
        ### Access control
        $accessibleBy
        EOT;

        // Append the optional description if provided.
        if ($description) {
            $markdown .= <<<EOT
            
            
            ### Description
            $description
            EOT;
        }

        return $markdown;
    }
}
