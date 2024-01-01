<?php
    namespace App\Plugins\Login\Providers;

    use Illuminate\Support\ServiceProvider;
    use App\Plugins\Login\Controllers\LoginController;
    use App\PluginManager;

    class LoginPluginServiceProvider extends ServiceProvider
    {
        public function register()
        {
            // Register any services specific to the LoginPluginServiceProvider service provider
        }

        public function boot()
        {
            // Bootstrapping code: Register routes, views, migrations, etc.
             $this->publishes([
                __DIR__ . '/../Assets' => public_path('Login'),], 'public');

            $pluginManager = app(PluginManager::class);
            $route = $pluginManager->getPluginRoute('Login');

            $this->loadRoutesFrom(__DIR__."/../Routes/$route.php");
            $this->loadViewsFrom(__DIR__.'/../Views', 'Login');
            $this->loadMigrationsFrom(__DIR__.'/../Migrations');

            // Register the controllers
            $this->registerControllers();
        }

        protected function registerControllers()
        {
            $this->app->make(LoginController::class);
            // You can make other controllers similarly
        }
    }

?>
