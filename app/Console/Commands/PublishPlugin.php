<?php

namespace App\Console\Commands;

use ZipArchive;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PublishPlugin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:publish {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publis plugin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pluginName = ucfirst($this->argument('name'));

        $author = ucwords($this->ask('Author name'));
        // Define paths
        $pluginPath = app_path("Plugins/{$pluginName}");
        $zipFilePath = storage_path("app/{$pluginName}.zip");

        // Zip the plugin folder
        $zip = new ZipArchive();
        $zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($pluginPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($pluginPath) + 1);

                $zip->addFile($filePath, $relativePath);
            }
            else{
                $zip->addEmptyDir($file->getFilename());
            }
        }

        $zip->close();

        // Send the file to another server
        $contents = fopen("$zipFilePath", "r");

        $response = Http::attach('file', $contents)->post("http://localhost:8000/api/plugin/publish", ["author" => $author, 'plugin_name' => $pluginName]);

        // Optionally, you can get the response body
        $responseBody = $response->body();
        $this->info($responseBody);
    }
}
