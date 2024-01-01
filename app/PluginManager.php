<?php
    namespace App;
    use App\Models\Plugin;
    use Illuminate\Support\Facades\File;

    use Illuminate\Support\Collection;

    class PluginManager{
        protected $registeredPlugins = [];
        protected $app;

        public function __construct($app)
        {
            $this->app = $app;
        }
        public function registerPlugin($pluginName, $serviceProviderClass, $description, $routeType)
        {
            Plugin::insert([
                'name' => $pluginName,
                'service_provider' => $serviceProviderClass,
                'description' => $description,
                'route_type' => $routeType,
            ]);

        }



        // Check if plugin is registered
        public function isPluginRegistered($pluginName)
        {
            return isset($this->registeredPlugins[$pluginName]);
        }


        public function getRegisteredPlugins(): Collection
        {
            $plugins = Plugin::all();

                return $plugins->map(function ($plugin) {
                    return [
                        'name' => $plugin->name,
                        'service_provider' => $plugin->service_provider,
                        'description' => $plugin->description,
                        'route_type' => $plugin->route_type,
                        'status' => $plugin->status,
                        'version' => $plugin->version,
                    ];

                    $this->registeredPlugins[$plugin->service_provider];
                });


        }

        public function getPluginRoute($pluginName){
            return Plugin::where('name', $pluginName)->first()->route_type;
        }

        // validates plugin for errors
        public function validatePlugin($pluginName){
            if (!$this->isPluginRegistered($pluginName)) {
                throw new \Exception("Plugin '{$pluginName}' is not registered.");
            }

            // Check if the plugin has a service provider
            if (empty($this->registeredPlugins[$pluginName])) {
                throw new \Exception("Plugin '{$pluginName}' does not have a service provider.");
            }

        }

    }
?>
