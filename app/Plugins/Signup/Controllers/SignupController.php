<?php
    namespace App\Plugins\Signup\Controllers;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;

    class SignupController extends Controller
    {
        public function index(){
            return view('Signup::index');
            // less is more
        }
    }
?>
