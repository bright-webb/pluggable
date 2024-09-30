<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Pipeline\Pipeline;

use Illuminate\Http\Request;

class RequestController extends Controller
{
    protected $app;
    protected $router;

    public function __construct(Application $app, Router $router)
    {
        $this->app = $app;
        $this->router = $router;
        // $this->middleware('auth:api', ['except' => ['login', 'signup', 'password/reset', 'reset', 'test', 'getUser']]);
    }
    public function index(){
        return response()->json(['message' => 'hello world']);
    }

    public function handle(Request $request)
    {
        $method = strtoupper($request->method());
        $payload = $this->getPayload($request);
        $response = [];
    
        foreach ($payload as $endpoint => $data) {
            if(isset($data) && !empty($data)){
                $data = $this->decryptData($data);
            }
           
         
            $point = preg_replace('/__/', '', $endpoint);
            $uri = "api/{$point}";
            $hotRequest = $this->createHotRequest($uri, $method, $data);
    
            app()->instance('request', $hotRequest);
    
            try {
                $route = Route::getRoutes()->match($hotRequest);
    
                if ($route) {
                    $result = $this->callRouteAction($route, $hotRequest);
                    $response[$endpoint] = $this->handleControllerResponse($result);
                } else {
                    $response[$endpoint] = $this->formatResponse(null, 'Route not found', 404);
                }
            } catch (\Exception $e) {
                $response = $this->formatResponse(null, $e->getMessage(), 500);
            } finally {
                app()->instance('request', $request);
            }
        }
    
        return response()->json($response);
    }
    
    private function getPayload(Request $request)
    {
        if ($request->isJson()) {
            return $request->json()->all();
        } else {
            $payload = [];
            foreach ($request->all() as $key => $value) {
                if ($value instanceof \Illuminate\Http\UploadedFile) {
                    $payload[$key] = $value;
                } elseif (is_string($value)) {
                    $decodedValue = json_decode($value, true);
                    $payload[$key] = (json_last_error() == JSON_ERROR_NONE) ? $decodedValue : $value;
                } else {
                    $payload[$key] = $value;
                }
            }
            return $payload;
        }
    }
    
    private function createHotRequest($uri, $method, $data)
    {
        $hotRequest = Request::create($uri, $method);
        
        if ($method == 'GET') {
            $hotRequest->query->add($data);
        } else {
            $hotRequest->request->add($data);
            
            // Handle file uploads
            foreach ($data as $key => $value) {
                if ($value instanceof \Illuminate\Http\UploadedFile) {
                    $hotRequest->files->set($key, $value);
                }
            }
        }
    
        return $hotRequest;
    }
    
    private function callRouteAction($route, $request)
    {
        $action = $route->getAction();
    
        if (isset($action['controller'])) {
            list($controller, $method) = explode('@', $action['controller']);
        } elseif (is_array($action['uses'])) {
            list($controller, $method) = $action['uses'];
        } else {
            throw new \Exception('Unable to determine controller');
        }
    
        if (!class_exists($controller)) {
            throw new \Exception('Controller not found');
        }
    
        $controllerInstance = app()->make($controller);
        $parameters = array_merge($route->parameters(), ['request' => $request]);
    
        return app()->call([$controllerInstance, $method], $parameters);
    }
    

    private function handleControllerResponse($result)
    {
        if ($result instanceof JsonResponse) {
            $content = json_decode($result->getContent(), true);
            $status = $result->getStatusCode();
            return $this->formatResponse($content, null, $status);
        } elseif (is_array($result)) {
            return $this->formatResponse($result);
        } else {
            return $this->formatResponse($result);
        }
    }

    private function formatResponse($data, $error = null, $status = 200)
    {
        return [
            'data' => $data,
            'error' => $error,
            'status' => $status,

        ];
    }

    private function decryptData($encryptedData)
    {
        $secretKey = base64_decode('4cITFJhzaqVZjlQBSxMROSjNv4tCfc5yVkbFY80743k=');
        $iv = '1234567890123456';

        $encryptedData = base64_decode($encryptedData);

        // Decrypt the data using openssl_decrypt
        $decrypted = openssl_decrypt($encryptedData, 'aes-256-cbc', $secretKey, OPENSSL_RAW_DATA, $iv);

        return json_decode($decrypted, true);
    }
}
