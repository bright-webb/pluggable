<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakePluginPolicy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:policy {plugin} {policy}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a policy for a plugin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pluginName = $this->argument('plugin');
        $policyName = $this->argument('policy');

        // Create policy
        $this->call('make:policy', [
            'name' => "$policyName",
            '--model' => 'AnyModel',
        ]);

        // check if policies directory exists in the plugin
        if (!file_exists(plugin_path("$pluginName/Policies"))) {
            mkdir(plugin_path("$pluginName/Policies"), 0755, true);
        }

        // Move policy to plugin policies directory
        $this->moveFile(
            app_path("Policies/$policyName.php"),
            app_path("Plugins/$pluginName/Policies/$policyName.php")
        );
        $this->info("Policy created successfully!");
    }

    protected function moveFile($from, $to)
    {
        $this->info("Moving $from to $to");

        if (File::move($from, $to)) {
            $this->info('File moved successfully.');
        } else {
            $this->error('Failed to move file.');
        }
    }
}
