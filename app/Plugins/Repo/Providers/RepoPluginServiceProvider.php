<?php
    namespace App\Plugins\Repo\Providers;

    use Illuminate\Support\ServiceProvider;
    use App\Plugins\Repo\Controllers\RepoController;
    use App\PluginManager;

    class RepoPluginServiceProvider extends ServiceProvider
    {
        public function register()
        {
            // Register any services specific to the RepoPluginServiceProvider service provider
        }

        public function boot()
        {
            // Bootstrapping code: Register routes, views, migrations, etc.
             $this->publishes([
                __DIR__ . '/../Assets' => public_path('Repo'),], 'public');

            $pluginManager = app(PluginManager::class);
            $route = $pluginManager->getPluginRoute('Repo');

            $this->loadRoutesFrom(__DIR__."/../Routes/$route.php");
            $this->loadViewsFrom(__DIR__.'/../Views', 'Repo');
            $this->loadMigrationsFrom(__DIR__.'/../Migrations');

            // Register the controllers
            $this->registerControllers();
        }

        protected function registerControllers()
        {
            $this->app->make(RepoController::class);
            // You can make other controllers similarly
        }
    }

?>
