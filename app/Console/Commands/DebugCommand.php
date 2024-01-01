<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PluginManager;

class DebugCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug information for PluginManager';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pluginManager = app(PluginManager::class);

        $this->info('Registered Plugins:');
        $this->table(['Plugin Name', 'Service Providers'], $pluginManager->getRegisteredPlugins()->toArray());
    }
}
