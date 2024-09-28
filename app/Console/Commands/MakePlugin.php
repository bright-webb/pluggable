<?php

namespace App\Console\Commands;

use App\PluginManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakePlugin extends Command
{
    protected $pluginManager;
    public function __construct(PluginManager $pluginManager)
    {
        parent::__construct();
        $this->pluginManager = $pluginManager;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:plugin {name} {--D|description=} {--type=web}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = strtolower($this->argument('name'));
        $description = $this->option('description');
        $routeType = $this->option('type');
        // check if name contains  'plugin' and remove the 'plugin'
        if (strpos($name, 'plugin') !== false) {
            $name = str_replace('plugin', '', $name);
        }


        // create the plugin directory
        $path = app_path('Plugins/'. ucfirst($name));

        // check if the plugin directory exists
        if(File::exists($path)){
            $this->error('Plugin already exists!');
            return 1;
        }

        // Create plugin directory
        File::makeDirectory(ucfirst($path), 0755, true, true);

        // show info
        $this->info("Plugin '$name' created successfully!");

        // Define subdirectories and file to create
        $subdirectories = [
            'Controllers',
            'Providers',
            'Routes',
            'Views',
            'tests',
            'config'
        ];

        $files = [ucfirst($name).'Plugin.php'];


        // Create subdirectories
        foreach($subdirectories as $subdirectory){
            File::makeDirectory($path.'/'.$subdirectory, 0755, true, true);

            // show info message
            $this->info($subdirectory. ' created successfully');
        }

        // loop through files and create them
        foreach($files as $file){
            File::put($path.'/'.$file, '');
            // show info message
            $this->info($file. ' created successfully');
        }

        // create a file and store in the Routes directory
        $routesDirectory = app_path("Plugins/{$name}/Routes");

        // check route type
        if($routeType === 'web'){
            $routesFile = $routesDirectory . DIRECTORY_SEPARATOR . 'web.php';
            file_put_contents($routesFile, '');
        }
        else{
            $routesFile = $routesDirectory . DIRECTORY_SEPARATOR . 'api.php';
            file_put_contents($routesFile, '');
        }


       $serviceProviderName = ucfirst($name) . 'PluginServiceProvider';
       $serviceProviderFile = $path . "/Providers/{$serviceProviderName}.php";
        $this->insertFile($serviceProviderFile, 'service-provider', [
            '{{ServiceProviderClass}}' => $serviceProviderName,
            '{{Plugin}}' => ucfirst($name),
        ]);

        // Register the plugin provider for the plugin
        $name = ucfirst($name);
        $serviceProviderClass = "App\\Plugins\\$name\\Providers\\$serviceProviderName";
        $this->pluginManager->registerPlugin($name, $serviceProviderClass, $description, $routeType);

                // Create a file in the config directory and insert line of code
                $configFile = $path . "/config/plugin.php";

                $content = <<<CONTENT
                <?php
                /*
                |--------------------------------------------------------------------------
                | Plugin Configuration
                |--------------------------------------------------------------------------
                |
                | Here you can configure your plugin.
                |
                */

                // Middleware
                return [
                    // Middleware
                    'middleware' => [
                        // Register your plugin middleware here
                    ],

                    // Dependencies
                    'dependencies' => [
                        // Specify your plugin dependencies here
                        // 'laravel/framework' => '9.0.*',
                    ],

                    // Plugin Dependencies
                    'plugin_dependencies' => [
                        // Specify your plugin dependencies here
                        // 'Test',
                    ],

                    // Plugin Information
                    'plugin_info' => [
                        'name' => '{$name}',
                        'version' => '1.0.0',
                        'description' => '{$description}',
                        'service_provider' => '{$serviceProviderClass}',
                        'type' => '{$routeType}',
                    ],
                ];
                ?>
                CONTENT;

               File::put($configFile, $content);

        // insert sample unit test code
        $sampleCode = <<<PHP
        <?php

        namespace App\Plugin\{$name}\Test;

        use Tests\TestCase;

        class {$name}UnitTest extends TestCase
        {
            public function test_that_this_is_true()
            {
                \$this->assertTrue(true);
            }
        }
        PHP;

        File::put($path . '/tests/' . ucfirst($name) . 'UnitTest.php', $sampleCode);



        // insert plugin.php boilerplate
        $this->insertFile($path . '/'.ucfirst($name).'plugin.php',  'plugin', ['{{Plugin}}' => ucfirst($name)]);

        // insert config.php boilerplate
        $this->insertFile($path . '/config.php',  'config', ['']);

        // insert routes.php boilerplate
        if($routeType == 'web'){
            $this->createDirectories($path);
            $this->insertFile($path . '/Routes/web.php',  'web', ['{{Plugin}}'  => ucfirst($name), '{{plugin}}' => strtolower($name)]);
        }
        else{
            $this->insertFile($path . '/Routes/api.php',  'api', ['{{Plugin}}'  => ucfirst($name), '{{plugin}}' => strtolower($name)]);
        }


        if($routeType == 'web'){
            // insert web controller boilerplate
            $this->insertFile($path . '/Controllers/'.ucfirst($name).'Controller.php', 'webcontroller', ['{{Plugin}}'  => ucfirst($name)]);
            $view = <<<HTML
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Welcome page</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            margin: 0;
                            padding: 0;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            height: 100vh;
                        }

                        .container {
                            text-align: center;
                        }

                        .greeting {
                            font-size: 2em;
                            color: #333;
                        }
                    </style>
                </head>
                <body>

                <div class="container">
                    <div class="greeting">
                        Hello from $name controller!
                    </div>
                </div>

                </body>
                </html>

            HTML;

            File::put($path . '/Views/index.blade.php', $view);
        }
        else{
            // insert controller.php boilerplate
            $this->insertFile($path . '/Controllers/'.ucfirst($name).'Controller.php', 'controller', ['{{Plugin}}'  => ucfirst($name)]);
        }

        if($routeType == 'web'){
            $this->line("Don't forget to run 'php artisan vendor:publish --tag=public --force' after building your assets");
        }
        // return success message
        return 0;


    }

    /*
     * Insert file into the given path
     * file
     * stub name
     * placeholder
    */
    protected function insertFile($file, $stubName, $replacements)
    {
        $stub = $this->getStubContent($stubName);

        foreach ($replacements as $placeholder => $replacement) {
            $stub = str_replace($placeholder, $replacement, $stub);
        }

        // $stub = str_replace($placeholder, $target, $stub);
        $stub = str_replace('{{__DIR__}}', __DIR__, $stub);

        // Write the content to the file
        file_put_contents($file, $stub);

        $this->info("$stubName 'file' created successfully.");
    }

    protected function getStubContent($name)
    {
        $stubPath = resource_path("stubs/{$name}.stub");

        if (file_exists($stubPath)) {
            return file_get_contents($stubPath);
        }

        return '';
    }


    // create assets directories
    protected function createDirectories($pluginPath)
    {
        // Create Assets directory
        $assetsPath = $pluginPath . '/Assets';
        File::makeDirectory($assetsPath);

        // Create subdirectories for assets (css, js, images)
        foreach (['css', 'js', 'images'] as $assetType) {
            File::makeDirectory("$assetsPath/$assetType");
        }
    }
}

