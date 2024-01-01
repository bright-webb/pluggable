<?php

namespace App\Providers;

use App\PluginManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PluginManager::class, function ($app) {
            return new PluginManager($app);
        });


        // check if plugins table exists and has plugins, then register plugins
        if (Schema::hasTable('plugins')) {
            $plugins = DB::table('plugins')->get();
            if (count($plugins) > 0) {
                foreach ($plugins as $plugin) {
                    if($plugin->is_enabled == 1){
                        app()->register($plugin->service_provider);
                    }

                }
            }
        }

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }


}
