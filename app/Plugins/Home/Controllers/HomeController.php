<?php
    namespace App\Plugins\Home\Controllers;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use App\Plugins\Repo\Services\DeliveryService;

    class HomeController extends Controller
    {
        protected $service;
        public function __construct(){
            $this->service =  new DeliveryService();
        }

        public function index(){
            return view('Home::index');
        }

        public function plugins(){
            $plugins = $this->getPlugins();
            return view('Home::plugins')->with('plugins' , $plugins);
        }

        public function search(Request $request){
            $query = $request->input('query');
            $search = $this->service->search($query);

             echo count($search);
        }
    }
?>
