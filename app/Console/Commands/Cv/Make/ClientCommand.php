<?php

declare(strict_types=1);

namespace App\Console\Commands\Cv\Make;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;

class ClientCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "cv:make:client
                            {name : The OAuth client's name}
                            {redirect-uri : The OAuth client's redirect URI}
                            {--first-party : Specify a first party client}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new OAuth client';

    /**
     * @var \Laravel\Passport\ClientRepository
     */
    protected $clients;

    /**
     * AdminCommand constructor.
     *
     * @param \Laravel\Passport\ClientRepository $clients
     */
    public function __construct(ClientRepository $clients)
    {
        parent::__construct();

        $this->clients = $clients;
    }

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle(): void
    {
        $client = Passport::client()->forceFill([
            'user_id' => null,
            'name' => $this->argument('name'),
            'secret' => Str::random(40),
            'redirect' => $this->argument('redirect-uri'),
            'personal_access_client' => false,
            'password_client' => false,
            'first_party_client' => $this->option('first-party'),
            'revoked' => false,
        ]);

        $client->save();

        $this->info('New client created successfully.');
        $this->line('<comment>Client ID:</comment> ' . $client->id);
        $this->line('<comment>Client secret:</comment> ' . $client->secret);
    }
}
