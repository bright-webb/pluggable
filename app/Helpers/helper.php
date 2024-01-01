<?php
    /*
        This is a helper function that will contain reusable code and
        can be called from anywhere in the application.
    */
    use Illuminate\Support\Facades\Config;

    // define the plugin_path in the application
    function plugin_path() {
        return app_path('Plugins');
    }


    function middleware($pluginName, $key)
    {
        $configFilePath = base_path("app/Plugins/{$pluginName}/config/plugin.php");

        if (file_exists($configFilePath)) {
            $config = require $configFilePath;

            // Assuming the middleware configuration key is 'middleware'
            return $config['middleware'][$key] ?? null;
        }

        return null;
    }
?>
