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
        $this->info('Plugin commands:');
        $this->table(['command', 'Description'], [
            ['plugin:model {plugin} {model}', 'Used to create model class for the defined plugin'],
            ['plugin:job {plugin} {job}', 'Used to create job class for the defined plugin'],
            ['plugin:service {plugin} {service}', 'Used to create service class for the defined plugin'],
            ['plugin:mail {plugin} {email}', 'Used to create email class for the defined plugin'],
            ['plugin:list', 'List all plugins'],
            ['plugin:middleware {plugin} {middleWareName}', 'Create middleware for the defined plugin'],
            ['plugin:disable {plugin}', 'Disable plugin'],
            ['plugin:enable {plugin}', 'Enable plugin'],
            ['plugin:policy {plugin} {policyName}', 'Create policy for the plugin'],
            ['plugin:controller {plugin} {controller}', 'Create controller for the plugin'],
            ['plugin:make-migration {migration} {plugin}', 'Create a migration for the plugin'],
        ]);
    }
}
