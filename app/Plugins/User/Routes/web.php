
<?php
// app/Plugins/User/Routes/api.php
use Illuminate\Support\Facades\Route;
use App\Plugins\User\Controllers\UserController;

Route::prefix('user')->group(function () {
    Route::get('index', [UserController::class, 'index']);
    // Add other routes for your controller
});

?>
