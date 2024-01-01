<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakePluginMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:mail {name} {mail}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a mail class for a plugin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $mail = $this->argument('mail');

        $path = app_path("Plugins/{$name}/Mail");
        $viewsPath = app_path("Plugins/{$name}/Views");

        // Check if directories exist
        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        // Generate the mail class
        $this->call('make:mail', ['name' => "App\\Plugins\\$name\\Mail\\$mail"]);

        // Generate the mail view file
        $this->generateMailView($viewsPath, $mail);

        $this->info("$mail Mail for '$name' created successfully");
    }

    protected function generateMailView($viewsPath, $mail)
    {
        // Generate default mail.blade.php content
        $content = <<<PHP
<!DOCTYPE html>
<html>
<head>
    <title>{{ \$title }}</title>
</head>
<body>
    <h1>{{ \$title }}</h1>
    <p>{{ \$content }}</p>
</body>
</html>
PHP;

        // Save content to mail.blade.php
        $viewFileName = "{$mail}.blade.php";
        File::put("$viewsPath/$viewFileName", $content);
    }
}
