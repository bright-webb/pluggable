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
        'name' => 'Home',
        'version' => '1.0.0',
        'description' => '',
        'service_provider' => 'App\Plugins\Home\Providers\HomePluginServiceProvider',
        'route_type' => 'web',
    ],
];
?>