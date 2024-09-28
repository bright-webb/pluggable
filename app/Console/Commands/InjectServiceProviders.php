<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\PluginManager;

class InjectServiceProviders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $pluginManager;
    public function __construct(PluginManager $pluginManager)
    {
        parent::__construct();
        $this->pluginManager = $pluginManager;
    }
    
    protected $signature = 'plugin:inject-service';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register plugin service providers to the database';

    /**
     * Execute the console command.
     */

     

    public function handle()
    {
        $pluginDirectories = glob(app_path('Plugins/*'), GLOB_ONLYDIR);

        foreach ($pluginDirectories as $pluginDirectory) {
            // Load plugin configuration
            $configFile = $pluginDirectory . '/config/plugin.php';
            if (File::exists($configFile)) {
                $pluginConfig = include $configFile;

                // Extract plugin information
                $pluginInfo = $pluginConfig['plugin_info'] ?? null;
                if ($pluginInfo) {
                    $pluginName = $pluginInfo['name'];
                    $description = $pluginInfo['description'];
                    $serviceProviderClass = $pluginInfo['service_provider'];
                    $routeType = $pluginInfo['type'];


                //     // Register service provider
                    $this->pluginManager->registerPlugin($pluginName, $serviceProviderClass, $description, $routeType);
                }
            }
        }

        $this->info('Service providers injected into the database successfully.');
    }
}
