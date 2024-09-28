<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

// ... rest of your code


class PluginMakeMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:make-migration {migration} {plugin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a migration for a plugin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $migration = $this->argument('migration');
        $plugin = $this->argument('plugin');
        $migrationPath = app_path("Plugins/$plugin/Migrations");
       // create migration for the plugin
       if (!file_exists($migrationPath)) {
        mkdir($migrationPath, 0755, true);
            }

            $this->call('make:migration', [
                'name' => "create_{$migration}_table",
                '--path' => "app/Plugins/$plugin/Migrations",
            ]);
        $this->info("Migration created successfully for $plugin");
    }

    protected function executeCommand($command, $path)
    {
        // Change directory to the specified path
        chdir($path);

        // Execute the command
        $output = [];
        $returnValue = null;
        exec($command, $output, $returnValue);

        // Output command results
        foreach ($output as $line) {
            $this->line($line);
        }

        // Return the command exit code
        return $returnValue;
    }
}
