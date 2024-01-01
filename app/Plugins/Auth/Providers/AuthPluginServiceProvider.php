<?php
    namespace App\Plugins\Auth\Providers;

    use Illuminate\Support\ServiceProvider;
    use App\Plugins\Auth\Controllers\AuthController;
    use App\PluginManager;

    class AuthPluginServiceProvider extends ServiceProvider
    {
        public function register()
        {
            // Register any services specific to the AuthPluginServiceProvider service provider
        }

        public function boot()
        {
            // Bootstrapping code: Register routes, views, migrations, etc.
             $this->publishes([
                __DIR__ . '/../Assets' => public_path('Auth'),], 'public');

            $pluginManager = app(PluginManager::class);
            $route = $pluginManager->getPluginRoute('Auth');

            $this->loadRoutesFrom(__DIR__."/../Routes/$route.php");
            $this->loadViewsFrom(__DIR__.'/../Views', 'Auth');
            $this->loadMigrationsFrom(__DIR__.'/../Migrations');

            // Register the controllers
            $this->registerControllers();
        }

        protected function registerControllers()
        {
            $this->app->make(AuthController::class);
            // You can make other controllers similarly
        }
    }

?>
