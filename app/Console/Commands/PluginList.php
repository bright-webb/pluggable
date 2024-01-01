<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PluginManager;

class PluginList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List Plugins';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pluginManager = app(PluginManager::class);

        $this->info('Registered Plugins:');
        $this->table(['Plugin Name', 'Service Providers', 'Description', "Author", "Route Type", "Status", "Version"], $pluginManager->getRegisteredPlugins()->toArray());
    }
}
