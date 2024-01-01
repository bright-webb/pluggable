<?php
    namespace App\Plugins\User\Controllers;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;

    class UserController extends Controller
    {
        public function index(){
            return view('User::index');
            // less is more
        }
    }
?>
