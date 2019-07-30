<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        Route::bind('admin', function ($value): Admin {
            return Admin::find($value)
                ?? optional(request()->user('api'))->admin
                ?? abort(Response::HTTP_NOT_FOUND);
        });

        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapPassportRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::middleware('api')
            ->namespace("{$this->namespace}\V1")
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "passport" routes for the application.
     *
     * These routes are for OAuth.
     */
    protected function mapPassportRoutes(): void
    {
        Route::prefix('oauth')
            ->as('passport.')
            ->group(base_path('routes/passport.php'));
    }
}
