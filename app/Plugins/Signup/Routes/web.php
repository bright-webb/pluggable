
<?php
// app/Plugins/Signup/Routes/api.php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use App\Plugins\Signup\Controllers\SignupController;

Route::prefix('signup')->group(function () {
    Route::get('index', [SignupController::class, 'index']);
    // Add other routes for your controller
});


?>
