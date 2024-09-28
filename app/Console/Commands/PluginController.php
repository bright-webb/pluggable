<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PluginController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:controller {plugin} {controller}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make controller for the plugin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pluginName = $this->argument('plugin');
        $controller = $this->argument('controller');

       // Check if contoller directory exists, if not, create it
       if (!File::exists(app_path("Plugins/$pluginName/Controllers"))) {
        File::makeDirectory(app_path("Plugins/$pluginName/Controllers"), 0755, true);
    }

    // create a controller
    $this->call('make:controller', ['name' => "App\\Plugins\\$pluginName\\Controllers\\$controller"]);
    $this->info('Controller created successfully for the plugin.');

    }
}
