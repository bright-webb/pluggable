<?php
    namespace App\Plugins\User\Providers;

    use Illuminate\Support\ServiceProvider;
    use App\Plugins\User\Controllers\UserController;
    use App\PluginManager;

    class UserPluginServiceProvider extends ServiceProvider
    {
        public function register()
        {
            // Register any services specific to the UserPluginServiceProvider service provider
        }

        public function boot()
        {
            // Bootstrapping code: Register routes, views, migrations, etc.

            $pluginManager = app(PluginManager::class);
            $route = $pluginManager->getPluginRoute('User');

            $this->loadRoutesFrom(__DIR__."/../Routes/$route.php");
            $this->loadViewsFrom(__DIR__.'/../Views', 'User');
            $this->loadMigrationsFrom(__DIR__.'/../Migrations');

            // Register the controllers
            $this->registerControllers();
        }

        protected function registerControllers()
        {
            $this->app->make(UserController::class);
            // You can make other controllers similarly
        }
    }

?>
