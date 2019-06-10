<?php

declare(strict_types=1);

namespace App\Console\Commands\Cv\Make;

use Illuminate\Console\Command;

class ModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cv:make:model 
                            {name : The name of the class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent model class along with useful traits';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle(): void
    {
        $name = $this->argument('name');

        if (!$this->checkIfModelExists($name)) {
            return;
        }

        $this->makeModelClass($name);
        $this->makeMutatorsTrait($name);
        $this->makeRelationshipsTrait($name);
        $this->makeScopesTrait($name);

        $this->info("{$name} model and traits created successfully.");
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function checkIfModelExists(string $name): bool
    {
        if (is_file(app_path("Models/{$name}.php"))) {
            $this->error("{$name} model already exists.");

            return false;
        }

        if (is_file(app_path("Models/Mutators/{$name}Mutators.php"))) {
            $this->error("{$name} mutators already exist.");

            return false;
        }

        if (is_file(app_path("Models/Relationships/{$name}Relationships.php"))) {
            $this->error("{$name} relationships already exist.");

            return false;
        }

        if (is_file(app_path("Models/Scopes/{$name}Scopes.php"))) {
            $this->error("{$name} scopes already exist.");

            return false;
        }

        return true;
    }

    /**
     * @param string $name
     */
    protected function makeModelClass(string $name): void
    {
        $contents = <<<EOT
        <?php
        
        declare(strict_types=1);
        
        namespace App\Models;
        
        use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
        
        class {$name} extends BaseModel
        {
            use Mutators\\{$name}Mutators;
            use Relationships\\{$name}Relationships;
            use Scopes\\{$name}Scopes;
        
            //
        }
        
        EOT;

        file_put_contents(
            app_path("Models/{$name}.php"),
            $contents
        );
    }

    /**
     * @param string $name
     */
    protected function makeMutatorsTrait(string $name): void
    {
        $contents = <<<EOT
        <?php
        
        declare(strict_types=1);
        
        namespace App\Models\Mutators;
        
        trait {$name}Mutators
        {
            //
        }
        
        EOT;

        file_put_contents(
            app_path("Models/Mutators/{$name}Mutators.php"),
            $contents
        );
    }

    /**
     * @param string $name
     */
    protected function makeRelationshipsTrait(string $name): void
    {
        $contents = <<<EOT
        <?php
        
        declare(strict_types=1);
        
        namespace App\Models\Relationships;
        
        trait {$name}Relationships
        {
            //
        }
        
        EOT;

        file_put_contents(
            app_path("Models/Relationships/{$name}Relationships.php"),
            $contents
        );
    }

    /**
     * @param string $name
     */
    protected function makeScopesTrait(string $name): void
    {
        $contents = <<<EOT
        <?php
        
        declare(strict_types=1);
        
        namespace App\Models\Scopes;
        
        trait {$name}Scopes
        {
            //
        }
        
        EOT;

        file_put_contents(
            app_path("Models/Scopes/{$name}Scopes.php"),
            $contents
        );
    }
}
