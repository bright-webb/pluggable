
<?php
// app/Plugins/Repo/Routes/api.php
use Illuminate\Support\Facades\Route;
use App\Plugins\Repo\Controllers\RepoController;

Route::prefix('repo')->group(function () {
    Route::get('index', [RepoController::class, 'index']);
    // Add other routes for your controller
});

?>
