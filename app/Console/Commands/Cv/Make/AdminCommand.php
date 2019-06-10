<?php

declare(strict_types=1);

namespace App\Console\Commands\Cv\Make;

use App\Services\AdminService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
     * @var \App\Services\AdminService
     */
    protected $adminService;

    /**
     * AdminCommand constructor.
     *
     * @param \App\Services\AdminService $adminService
     */
    public function __construct(AdminService $adminService)
    {
        parent::__construct();

        $this->adminService = $adminService;
    }

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle(): void
    {
        $password = $this->option('password') ?? 'secret';

        DB::transaction(function () use ($password): void {
            $this->adminService->create([
                'name' => $this->argument('name'),
                'phone' => $this->argument('phone'),
                'email' => $this->argument('email'),
                'password' => $password,
            ]);
        });

        $this->warn("Admin successfully created with password: {$password}");
    }
}
