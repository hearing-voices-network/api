<?php

declare(strict_types=1);

namespace App\Console\Commands\Cv\Make;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Console\Command;

class AdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "cv:make:admin
                            {name : The admin's name}
                            {email : The admin's email}
                            {phone : The admin's phone number}
                            {--password= : The password to use}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $password = $this->option('password') ?? 'secret';

        // TODO: Refactor this to use a repository.
        db()->transaction(function () use ($password): void {
            Admin::create([
                'name' => $this->argument('name'),
                'phone' => $this->argument('phone'),
                'user_id' => User::create([
                    'email' => $this->argument('email'),
                    'password' => $password,
                    'email_verified_at' => now(),
                ])->id,
            ]);
        });

        $this->warn("Admin successfully created with password: {$password}");
    }
}
