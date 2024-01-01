<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response as ResponseFacade;
use ZipArchive;
use App\Models\Plugin;
use Illuminate\Support\Facades\DB;

class Publish extends Controller
{
    public function publish(Request $request){
        if($request->hasFile('file')){
            $request->validate([
                'file' => 'required|file|mimes:zip|max:10240', // Example validation rules for a ZIP file (max size: 10MB)
            ]);
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $author = $request->input('author');
            $pluginName = $request->input('plugin_name');
            // Storage::put("uploads/{$fileName}", file_get_contents($file->getRealPath()));
            $fileName = $file->getClientOriginalName();
            $uniqueIdentifier = uniqid();
            $fileName = "{$pluginName}_{$uniqueIdentifier}";
            if(Plugin::where('name', $pluginName)->exists()){
                // Build the filename with the unique identifier
                $zipFileName = "{$fileName}.zip";
            }
            else{
                // Build the filename with the unique identifier
                $zipFileName = "{$pluginName}.zip";
            }


            // Specify the storage path for the uploaded plugin
            $zipFilePath = storage_path("app/uploads/{$zipFileName}");
            Storage::put("uploads/{$zipFileName}", file_get_contents($file->getRealPath()));

        // Example: Extract the contents of a ZIP file (if it's a ZIP file)
        if ($file->extension() === 'zip') {
            $zip = new ZipArchive;
            $zipFilePath = storage_path("app/uploads/{$zipFileName}");

            if ($zip->open($zipFilePath) === true) {
                // Extract files to a temporary directory
                $extractedPath = storage_path("app/extracted_files/{$zipFileName}");
                $zip->extractTo($extractedPath);
                $zip->close();

                // Search for config/plugin.php and get the plugin information
                $configFilePath = "{$extractedPath}/config/plugin.php";
                if (file_exists($configFilePath)) {
                    $pluginInfo = require $configFilePath;

                    // Save the plugin information to the database

                    if(Plugin::where('name', $pluginName)->exists()){
                        // make the name unique
                        $pluginName = $pluginName . '_' . uniqid();
                    }
                    DB::table('plugin_directory')->insert([
                        'name' => $fileName,
                        'version' => $pluginInfo['plugin_info']['version'],
                        'description' => $pluginInfo['plugin_info']['description'],
                        'author' => $pluginInfo['plugin_info']['author'],
                        'service_provider' => $pluginInfo['plugin_info']['service_provider'] ?? null,
                    ]);


                }

                // Optionally, you can delete the temporary extracted directory
                Storage::deleteDirectory("extracted_files/{$fileName}");
            }
        }
    }
    }

    public function getPlugin($name){
        $pluginInfo = Plugin::where('name', $name)->first();

        // Check if the plugin with the specified name exists
        if (!$pluginInfo) {
            abort(404, 'Plugin not found');
        }

        $filePath = storage_path("app/uploads/{$name}.zip");
        if(file_exists($filePath)){
            ob_end_clean();
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $name . '.zip"');
            header('Content-Length: ' . filesize($filePath));

            readfile($filePath);
        }
        else{
            header('HTTP/1.1 404 Not Found');
            echo 'File not found.';
            exit;
        }

    }
}
