<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakePluginJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:job {name} {job}';

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
        $job = $this->argument('job');

        $path = app_path("Plugins/$name/Jobs/");
        if(!File::exists($path)){
            File::makeDirectory($path, 0777, true, true);
        }

        $this->call('make:job', ['name' => "App\\Plugins\\$name\\Jobs\\$job"]);
        $this->info("$job for '$name' created successfully");
    }
}
