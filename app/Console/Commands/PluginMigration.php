<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PluginMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:migrate {plugin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate a plugin migration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $plugin = $this->argument('plugin');

        $this->executeCommand('php artisan migrate --path=app/Plugins/' . $plugin . '/Migrations --force', base_path());
        $this->info('Migration for plugin ' . $plugin . ' executed successfully.');
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
