<?php
    namespace App\Plugins\Login\Controllers;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;

    class LoginController extends Controller
    {
        public function index(){
            return view('Login::index');
            // less is more
        }
    }
?>
