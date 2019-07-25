<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Bus\DispatchesJobs;

abstract class EventSubscriber
{
    use DispatchesJobs;

    /**
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe(Dispatcher $events): void
    {
        $class = static::class;

        foreach ($this->mapping() as $event => $handler) {
            $events->listen($event, "{$class}@{$handler}");
        }
    }

    /**
     * @return string[]
     */
    abstract protected function mapping(): array;
}
