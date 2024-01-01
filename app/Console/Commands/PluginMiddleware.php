<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PluginMiddleware extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:middleware {plugin} {middleware}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create middleware for the plugin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pluginName = ucfirst($this->argument('plugin'));
        $middleware = $this->argument('middleware');

        // Check if middleware directory exists, if not, create it
        if (!File::exists(app_path("Plugins/$pluginName/Middleware"))) {
            File::makeDirectory(app_path("Plugins/$pluginName/Middleware"), 0755, true);
        }

         // create the middlware
         $this->call('make:middleware', ['name' => "App\\Plugins\\$pluginName\\Middleware\\$middleware"]);
         $this->info('Middleware successfully create for '.$pluginName.' plugin.');
    }
}
