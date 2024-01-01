<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use ZipArchive;
use Illuminate\Support\Facades\Storage;
use App\Models\Plugin;

class PluginDownload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:download {pluginName : Unique name of the plugin to download}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download plugin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pluginName = $this->argument('pluginName');
        $response = Http::get('http://localhost:8000/api/plugin/' . $pluginName, ['stream' => true]);
        $this->line('Downloading plugin...');
        if ($response->successful()) {
            // Save the plugin archive to a temporary file
            // $zipFilePath = storage_path("app/downloads/{$response->body()}");;


            // Extract the contents to the plugins directory
            $targetPath = app_path('Plugins/' . $pluginName);
            $destinationDirectory = "downloads";
            $destinationPath = "{$destinationDirectory}/{$pluginName}.zip";


             // Check if the destination directory exists, if not, create it
             if (!Storage::exists($destinationDirectory)) {
                Storage::makeDirectory($destinationDirectory);
            }

            // Save the downloaded file to the destination path
            $stream = $response->getBody()->detach();
            Storage::writeStream($destinationPath, $stream);
            fclose($stream);

            $this->info("plugin downloaded");

            // unzip the downloaded file
            $zip = new ZipArchive;
            if ($zip->open(storage_path("app/downloads/{$pluginName}.zip")) === true) {
                $zip->extractTo($targetPath);
                $zip->close();
                $this->info("plugin extracted");
                $this->processExtractedFiles($targetPath, $pluginName);
            }
            else{
                $this->error("Failed to extract plugin archive.");
            }
        } else {
            $this->error("Failed to download plugin '$pluginName'. Server response: " . $response->status());
        }
    }

    protected function processExtractedFiles($extractedPath, $pluginName)
    {
        // Iterate through the extracted files and look for plugin.php
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($extractedPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        $dependencies = [];
        $pluginDependencies = [];

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() === 'plugin.php') {
                // Parse the content of plugin.php
                $content = require $file->getPathname();

                // process plugin info
                Plugin::insert([
                    'name' => $pluginName,
                    'description' => $content['plugin_info']['description'] ?? '',
                    'version' => $content['plugin_info']['version'] ?? '',
                    'author' => $content['plugin_info']['author'] ?? '',
                    'service_provider' => $content['plugin_info']['service_provider'] ?? '',
                    'route_type' => $content['plugin_info']['route_type'] ?? '',
                ]);
                // Extract information including dependencies and plugin_dependencies
                $dependencies = $content['dependencies'] ?? [];
                $pluginDependencies = $content['plugin_dependencies'] ?? [];

            }
        }

        // Install Composer dependencies if any
        if (!empty($dependencies)) {
            $this->info("Installing Composer dependencies for '{$pluginName}'...");
            foreach($dependencies as $dependency => $version){
                $dependency = escapeshellarg($dependency);
                $version = escapeshellarg($version);

                // Execute the composer require command
                $this->executeCommand("composer install {$dependency}:{$version}", realpath(__DIR__ . '/..'));
            }
            $this->info("Composer dependencies installed successfully for '{$pluginName}'.");
            $this->newLine();
        }

        // Install Plugin dependencies if any
        if (!empty($pluginDependencies)) {
            $this->info("Installing Plugin dependencies for '{$pluginName}'...");
            foreach($pluginDependencies as $pluginDependency => $version){
                $this->executeCommand('php artisan plugin:download ' . ucfirst($pluginDependency), realpath(__DIR__ . '/..'));
            }
        }

        // Migrate if there are migration files
        $migrationPath = $extractedPath . '/Migrations';
        if (file_exists($migrationPath)) {
            $this->info("Running migrations for '{$pluginName}'...");
            $this->executeCommand('php artisan migrate --path=' . $migrationPath, realpath(__DIR__ . '/..'));
        }

        // Publish assets if Asset folder exists
        $assetPath = $extractedPath . '/Asset';
        if (file_exists($assetPath)) {
            $this->info("Publishing assets for '{$pluginName}'...");
            $this->executeCommand('php artisan vendor:publish --tag=public  --force', realpath(__DIR__ . '/..'));
        }
    }

    protected function executeCommand($command, $path)
    {
        // Change directory to the specified path
        chdir($path);

        // Execute the command
        $output = [];
        $returnValue = null;
        exec($command, $output, $returnValue);

        // Output command results
        foreach ($output as $line) {
            $this->line($line);
        }

        // Return the command exit code
        return $returnValue;
    }
}
