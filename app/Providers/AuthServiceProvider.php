<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::guessPolicyNamesUsing(function (string $modelClass): string {
            $className = class_basename($modelClass);

            return "App\\Policies\\{$className}Policy";
        });

        $this->registerPolicies();

        Passport::enableImplicitGrant();
        Passport::tokensExpireIn(Date::now()->endOfDay());
        Passport::refreshTokensExpireIn(Date::tomorrow()->endOfDay());
    }

    /**
     * Bootstrap any authentication / authorization services.
     */
    public function register(): void
    {
        Passport::ignoreMigrations();
    }
}
