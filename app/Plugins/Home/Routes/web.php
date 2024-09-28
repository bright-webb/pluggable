
<?php
// app/Plugins/Home/Routes/api.php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use App\Plugins\Home\Controllers\HomeController;

Route::prefix('home')->group(function () {
    Route::get('index', [HomeController::class, 'index']);
    Route::get('/plugins', [HomeController::class, 'plugins']);
    Route::post('/search', [HomeController::class, 'search']);
});


?>
