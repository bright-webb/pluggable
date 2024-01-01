<?php
    namespace App\Plugins\Auth\Controllers;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;

    class AuthController extends Controller
    {
        public function index(Request $request){
            return "Hello from Auth Controller";
            // less is more
        }
    }
?>
