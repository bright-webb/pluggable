
<?php
// app/Plugins/Login/Routes/api.php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use App\Plugins\Login\Controllers\LoginController;

Route::prefix('login')->group(function () {
    Route::get('index', [LoginController::class, 'index']);
    // Add other routes for your controller
});


?>
