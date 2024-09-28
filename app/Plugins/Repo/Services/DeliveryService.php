<?php
namespace App\Plugins\Repo\Services;
// use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use App\Plugins\Repo\Events\Delivery;

class DeliveryService
{
    public function RepoService()
    {
       // do something
    }

    public function getPlugins(){
        return DB::table('plugin_directory')->get();
    }

    public function search($search){
        return DB::table('plugin_directory')->where('plugin_name', 'like', '%'.$search.'%')->get();
    }
}
