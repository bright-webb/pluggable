<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class SeedTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-tables';

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
        $roles = DB::table('roles')->get();
        $categories = DB::table('categories')->get();
        $project_categories = DB::table('project_categories')->get();
        $skills = DB::table('skills')->get();
        $hash_tags = DB::table('hashtags')->get();
        $interests = DB::table('interests')->get();

        $seed_data = [
            'roles' => $roles,
            'categories' => $categories,
            'project_categories' => $project_categories,
            'skills' => $skills,
            'hashtags' => $hash_tags,
            'interests' => $interests,
        ];
        
       
        foreach($seed_data as $key=>$data){
            if(!is_dir(database_path('seeds'))){
                mkdir(database_path('seeds'));
            }
            
            $seedFilePath = database_path('seeds/'.$key.'_seed.json');
            file_put_contents($seedFilePath, json_encode($data));
        }

        $this->info('Seed file generated successfully!');
       
    }
}
