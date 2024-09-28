<?php

use App\Http\Controllers\Publish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



// Plugin: repo
Route::group(['namespace' => 'app\Plugins\repo\Routes'], function () {
    require app_path('Plugins/Repo/Routes/api.php');
});