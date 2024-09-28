<?php
    namespace App\Plugins\Repo\Controllers;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;

    class RepoController extends Controller
    {
        public function index(Request $request){
            return "Hello from Repo Controller";
            // less is more
        }
    }
?>
