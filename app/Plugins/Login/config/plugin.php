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
    'middleware' => [
        // Register your plugin middleware here
    ],
];

// Dependencies
return [
    'dependencies' => [
        // Specify your plugin dependencies here
        'laravel/framework' => '9.0.*',
    ],
];

// Plugin Information
return [
    'name' => 'login',
    'version' => '1.0.0',
    'description' => 'login plugin',
];
?>