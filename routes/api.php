<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\RequestController;
use Illuminate\Http\Request;
use App\Http\Controllers\Publish;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CorsMiddleware;
use Illuminate\Support\Facades\Storage;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/plugin/publish', [Publish::class, 'publish']);
Route::get('/plugin/{name}', [Publish::class, 'getPlugin']);

Route::get('/users', [RequestController::class, 'index']);

Route::group(['middleware' => 'api'], function ($router) {
	Route::match(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], 'action.json', [RequestController::class, 'handle']);
	Route::post('/request.json', [ApiController::class, 'handle']);
});




// Load plugin api routes
$global = glob(app_path('Plugins') . '/*', GLOB_ONLYDIR);
$plugins = [];
foreach($global as $folder){
	$plugins[] = basename($folder);
}

foreach($plugins as $plugin){
	// Iterate through each plugin
	$plugin_path = app_path('Plugins/' . $plugin);
	$plugin_routes = glob($plugin_path . '/Routes');
	 foreach($plugin_routes as $route){
		if(is_dir($route)){
			 $api_routes = glob($route . '/api.php');
			 if(!empty($api_routes)){
				 foreach($api_routes as $api){
					 $pluginName = basename($plugin_path);
					 $namespace = 'app\\Plugins\\' . $pluginName . '\\Routes';
					 Route::group(['namespace' => $namespace], function () use ($pluginName) {
						require app_path('Plugins/' . $pluginName . '/Routes/api.php');
					});
				 }
			 }
		}
	 }
}



