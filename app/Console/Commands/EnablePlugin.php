<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Plugin;

class EnablePlugin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:enable {name : The name of the plugin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable a plugin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $row = Plugin::where('name', $name)->update(['is_enabled' => true]);

        if ($row) {
            $this->info("Plugin '$name' enabled successfully.");
        } else {
            $this->error("Failed to enable plugin '$name'.");
        }
    }
}
