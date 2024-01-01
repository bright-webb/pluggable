<?php

namespace App\Console\Commands;

use App\Models\Plugin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PluginDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:delete {name : The name of the plugin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a plugin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        if($this->confirm("Do you really want to delete {$name} plugin?")){
            Plugin::where('name', $name)->delete();

             // Delete the directory
             $pluginPath = base_path("app/Plugins/$name");

             if (File::exists($pluginPath)) {
                 File::deleteDirectory($pluginPath);
                 $this->info("Plugin '$name' deleted successfully.");
             } else {
                 $this->error("Plugin directory not found: $pluginPath");
             }
        }
        else {
            $this->info("Deletion cancelled.");
        }
    }
}
