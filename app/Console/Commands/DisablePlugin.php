<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Plugin;

class DisablePlugin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:disable {name : The name of the plugin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable a plugin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $row = Plugin::where('name', $name)->update(['is_enabled' => false]);

        if ($row) {
            $this->info("Plugin '$name' disabled successfully.");
        } else {
            $this->error("Failed to disable plugin '$name'.");
        }
    }
}
