<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup', 'password/reset', 'reset', 'test', 'getUser']]);
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
            $hotRequest = $this->createHotRequest($uri, $method, $data, $request);
    
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
                $response = $this->formatResponse(null, [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ], 500);
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
            return $request->all();
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
