<?php

declare(strict_types=1);

namespace App\Providers;

use App\Sms\LogSmsSender;
use App\Sms\SmsSender;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
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
        // Use CarbonImmutable as the date instance.
        Date::use(CarbonImmutable::class);

        // Ignore the default Laravel Passport migrations as we have modified them.
        Passport::ignoreMigrations();

        // Add useful Request class macros.
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

        // Add useful Rule class macros.
        Rule::macro('min', function (int $min): string {
            return "min:{$min}";
        });

        Rule::macro('max', function (int $max): string {
            return "max:{$max}";
        });

        // Bind the SMS sender concrete implementation to the interface through configuration.
        switch (Config::get('sms.driver')) {
            case 'log':
            default:
                $this->app->singleton(SmsSender::class, LogSmsSender::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
