<?php
    namespace App\Plugins\Home\Providers;

    use Illuminate\Support\ServiceProvider;
    use App\Plugins\Home\Controllers\HomeController;
    use App\PluginManager;

    class HomePluginServiceProvider extends ServiceProvider
    {
        public function register()
        {
            // Register any services specific to the HomePluginServiceProvider service provider
        }

        public function boot()
        {
            // Bootstrapping code: Register routes, views, migrations, etc.
             $this->publishes([
                __DIR__ . '/../Assets' => public_path('Home'),], 'public');

            $pluginManager = app(PluginManager::class);
            $route = $pluginManager->getPluginRoute('Home');

            $this->loadRoutesFrom(__DIR__."/../Routes/$route.php");
            $this->loadViewsFrom(__DIR__.'/../Views', 'Home');
            $this->loadMigrationsFrom(__DIR__.'/../Migrations');

            // Register the controllers
            $this->registerControllers();
        }

        protected function registerControllers()
        {
            $this->app->make(HomeController::class);
            // You can make other controllers similarly
        }
    }

?>
