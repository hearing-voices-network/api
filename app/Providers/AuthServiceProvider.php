<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Laravel\Passport\RouteRegistrar;

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

        Passport::routes(function (RouteRegistrar $router): void {
            $router->forAuthorization();
            $router->forAccessTokens();
        });
        Passport::tokensExpireIn(Date::now()->addMinutes(30));
        Passport::refreshTokensExpireIn(Date::now()->addMinutes(60));
    }
}
