<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakePluginService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:service
                            {name : The name of the plugin}
                            {service : The name of the service}
                            {--l|listener : Create a listener}
                            {--e|event : Create an event}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a service for a plugin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $service = $this->argument('service');
        $servicePath = app_path("Plugins/{$name}/Services");
        $eventPath = app_path("Plugins/{$name}/Events");
        $listenerPath = app_path("Plugins/{$name}/Listeners");

        // Check if path exists
        if (!is_dir($servicePath)) {
            File::makeDirectory($servicePath, 0755, true, true);
        }

        if ($this->option('event') && !is_dir($eventPath)) {
            File::makeDirectory($eventPath, 0755, true, true);
        }

        if ($this->option('listener') && !is_dir($listenerPath)) {
            File::makeDirectory($listenerPath, 0755, true, true);
        }

        // Generate the service file
        $serviceContent = $this->generateServiceContent(ucfirst($name), ucfirst($service));
        $serviceFileName = $service . 'Service.php';
        File::put("$servicePath/$serviceFileName", $serviceContent);

        if ($this->option('event')) {
            $this->call('make:event', ['name' => "App\\Plugins\\$name\\Events\\$service"]);
            $this->info("$service Event for '$name' created successfully");
        }

        if ($this->option('listener')) {
            $this->call('make:listener', ['name' => "App\\Plugins\\$name\\Listeners\\$service"]);
            $this->info("$service Listener for '$name' created successfully");
        }

        $this->info("$service Service for '$name' created successfully");
    }

    protected function generateServiceContent($name, $service)
    {
        return <<<PHP
            <?php
            namespace App\Plugins\\$name\Services;
            // use Illuminate\Support\Facades\Redis;
            use App\Plugins\\$name\Events\\$service;

            class {$service}Service
            {
                public function {$name}Service()
                {
                    // Abracadabra
                    return "Hello, World!";
                }
            }
            PHP;
    }
}
