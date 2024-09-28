<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PluginNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:notification  {name} {notification}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $notification = $this->argument('notification');

        $path = app_path("Plugins/{$name}/Notification");

        // Check if directories exist
        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        // Generate the notification class
        $this->call('make:notification', ['name' => "App\\Plugins\\$name\\Notification\\$notification"]);

        $this->info("$notification Notification for '$name' created successfully");
    }


}
