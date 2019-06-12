<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Date::use(CarbonImmutable::class);

        Passport::ignoreMigrations();

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

        Rule::macro('min', function (int $min): string {
            return "min:{$min}";
        });

        Rule::macro('max', function (int $max): string {
            return "max:{$max}";
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
