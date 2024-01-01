<?php
    namespace App\Plugins\Signup\Providers;

    use Illuminate\Support\ServiceProvider;
    use App\Plugins\Signup\Controllers\SignupController;
    use App\PluginManager;

    class SignupPluginServiceProvider extends ServiceProvider
    {
        public function register()
        {
            // Register any services specific to the SignupPluginServiceProvider service provider
        }

        public function boot()
        {
            // Bootstrapping code: Register routes, views, migrations, etc.
             $this->publishes([
                __DIR__ . '/../Assets' => public_path('Signup'),], 'public');

            $pluginManager = app(PluginManager::class);
            $route = $pluginManager->getPluginRoute('Signup');

            $this->loadRoutesFrom(__DIR__."/../Routes/$route.php");
            $this->loadViewsFrom(__DIR__.'/../Views', 'Signup');
            $this->loadMigrationsFrom(__DIR__.'/../Migrations');

            // Register the controllers
            $this->registerControllers();
        }

        protected function registerControllers()
        {
            $this->app->make(SignupController::class);
            // You can make other controllers similarly
        }
    }

?>
