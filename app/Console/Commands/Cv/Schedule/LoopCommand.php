<?php

declare(strict_types=1);

namespace App\Console\Commands\Cv\Schedule;

use Illuminate\Console\Command;

class LoopCommand extends Command
{
    const ONE_MINUTE = 60;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cv:schedule:loop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the scheduler every minute';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        while (true) {
            $start = time();
            $this->call('schedule:run');
            $end = time();

            $timeTaken = $end - $start;
            $timeUntilOneMinute = static::ONE_MINUTE - $timeTaken;

            sleep($timeUntilOneMinute);
        }
    }
}
