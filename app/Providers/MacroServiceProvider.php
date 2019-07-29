<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerRequestMacros();
        $this->registerRuleMacros();
    }

    /**
     * Macros for the Request class.
     */
    protected function registerRequestMacros(): void
    {
        Request::macro('hasFilter', function (string $filter, $value = null): bool {
            $hasFilter = $this->has("filter.{$filter}");

            if ($value === null) {
                return $hasFilter;
            }

            return $hasFilter && $this->input("filter.{$filter}") === $value;
        });

        Request::macro('doesntHaveFilter', function (string $filter, $value = null): bool {
            return !$this->hasFilter($filter, $value);
        });
    }

    /**
     * Macros for the Rule class.
     */
    protected function registerRuleMacros(): void
    {
        Rule::macro('min', function (int $min): string {
            return "min:{$min}";
        });

        Rule::macro('max', function (int $max): string {
            return "max:{$max}";
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
