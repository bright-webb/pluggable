<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakePluginModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:model {plugin} {name} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a model and migration for a plugin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pluginName = $this->argument('plugin');
        $modelName = $this->argument('name');

        // Check if model directory exists, if not, create it
        if (!File::exists(app_path("Plugins/$pluginName/Models"))) {
            File::makeDirectory(app_path("Plugins/$pluginName/Models"), 0755, true);
        }

        // create a model
        $this->call('make:model', ['name' => "App\\Plugins\\$pluginName\\Models\\$modelName", '-m' => true]);


        // Move model to plugin directory
        // $this->moveFile(
        //     app_path("Models/$modelName.php"),
        //     app_path("Plugins/$pluginName/Models/$modelName.php")
        // );

        // Check if migration directory exists in the plugin
        if (!File::exists(app_path("Plugins/$pluginName/Migrations"))) {
            File::makeDirectory(app_path("Plugins/$pluginName/Migrations"), 0755, true);
        }

        // Move migration to plugin directory
        $migrationFile = $this->findLatestMigrationFile();
        $this->moveFile(
            database_path("migrations/$migrationFile"),
            app_path("Plugins/$pluginName/Migrations/$migrationFile")
        );

        $this->info('Model and migration created successfully for the plugin.');

    }

    protected function moveFile($from, $to)
    {
        $this->info("Moving $from to $to");

        if (File::move($from, $to)) {
            $this->info('File moved successfully.');
        } else {
            $this->error('Failed to move file.');
        }
    }

    protected function findLatestMigrationFile()
    {
        $files = File::files(database_path('migrations'));
        $latestMigration = last($files);

        return $latestMigration->getFilename();
    }
}
