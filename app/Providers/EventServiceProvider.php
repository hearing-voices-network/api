<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \Illuminate\Auth\Events\Registered::class => [
            \Illuminate\Auth\Listeners\SendEmailVerificationNotification::class,
        ],
        \App\Events\EndpointInvoked::class => [
            \App\Listeners\AuditLogger::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        \App\Listeners\ContributionEventSubscriber::class,
        \App\Listeners\EndUserEventSubscriber::class,
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        //
    }
}
