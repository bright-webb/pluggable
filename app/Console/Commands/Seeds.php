<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Seeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seeds';

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
        $path = database_path('seeds');
$files = scandir($path);
$files = array_diff($files, ['.', '..']);

foreach ($files as $file) {
    $filePath = database_path('seeds/'.$file);
    $jsonData = json_decode(file_get_contents($filePath), true);

    if ($jsonData === null) {
        $this->error("Error reading JSON from file: $file");
        continue;
    }

    $tableName = str_replace('_seed.json', '', $file);

    foreach ($jsonData as $item) {
        $row = DB::table($tableName)->where('id', $item['id'])->first();

        if (!$row) {
            DB::table($tableName)->insert($item);
            $this->info("Inserted data into $tableName");
        } else {
            $this->line("Data already exists in $tableName, skipping...");
        }
    }
}

$this->info("Operation completed");

    }
}
