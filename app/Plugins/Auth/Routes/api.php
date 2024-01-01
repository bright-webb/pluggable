
<?php
// app/Plugins/Auth/Routes/api.php
use Illuminate\Support\Facades\Route;
use App\Plugins\Auth\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::get('index', [AuthController::class, 'index']);
    // Add other routes for your controller
});

?>
