<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunPluginTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:test {plugin}';

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
        $plugin = $this->argument('plugin');
        $pluginPath = app_path('Plugins/' . $plugin);
        $pluginTestPath = glob($pluginPath . '/Tests');
        // print(gettype($pluginTestPath));
        foreach($pluginTestPath as $path){
            $testPath = glob($path. '/*');
            foreach($testPath as $test){
                if(!preg_match('/\.xml$/', $test)){
                    $dir = scandir($test);
                    foreach($dir as $file){
                        if($file !== '.' && $file !== '..'){
                            $this->info("Running test: $file");
                        }
                    }
                    Artisan::call('test', ['--path' => $test]);
                    $output = Artisan::output();

                    if (strpos($output, 'OK') !== false) {
                        $this->info("Test $file executed successfully.");
                    } else {
                        $this->error("Test $file failed.");
                        $this->line($output); 
                    }
                }
            }
        }
        // $pluginTestFiles = glob($pluginTestPath . '*.php');
        // foreach ($pluginTestFiles as $file) {
        //     Artisan::call('test', ['--path' => $file]);
        //     $this->line($file);
        // }
        // dd($pluginTestFiles);
        // print_r($pluginTestFiles);
        // dd($pluginTestPath);
    }
}
