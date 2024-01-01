<?php
namespace App\Plugins\Auth;

use App\Contracts\Plugin;

class AuthPlugin implements Plugin
{
    public function getName()
    {
        return 'User';
    }

    public function getDescription()
    {
        return 'Plugin for User-related functionality';
    }

    public function getVersion()
    {
        return '1.0';
    }

    public function register()
    {
        // Additional registration logic can go here
    }

    public function call()
    {
        // What can can't, can can do it
    }
}

?>
