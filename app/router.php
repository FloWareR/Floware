<?php 

namespace App;

use App\Controllers\Helper;
use App\Middleware\MiddlewareManager;

class Router {
    // Store routes in an array
    private $routes = [];
    private $middlewareManager;
    private $production;

    public function __construct($production) {
        $this->middlewareManager = new MiddlewareManager();
        $this->production = $production;
    }
    
    // Add a route to the routes array
    public function addRoute($method, $path, $controller, $action, $clearance) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action,
            'clearance' => $clearance
        ];
    }

    // Enroute the request to the appropriate controller and action
    public function enroute($requestUri, $requestMethod) {

        // Parse the request URI
        $path = parse_url($requestUri, PHP_URL_PATH);
        $path = trim($path, '/');
        $path = explode('/', $path);

        if (!$this->production) {
            array_shift($path);
            print_r($path);
        }
        if(!isset($path[0])) {
            $path[0] = 'views';
        }
        if(!isset($path[1])) {
            $path[1] = 'index';
        }

        // Loop through the routes array
        foreach ($this->routes as $route) {            
            if ($route['method'] === strtoupper($requestMethod) && $route['path'] === $path[0] && $route['action'] === $path[1]) {
                $controller = 'App\\Controllers\\' . $route['controller'];
                if(class_exists($controller)) {
                    $controller = new $controller();
                    if(method_exists($controller, $path[1])){ 
                        if(!$this->middlewareManager->authMiddleware($route)) {
                            Helper::sendResponse(401, ['error' => 'Unauthorized']);
                            return;
                        }
                        $controller->{$route['action']}(Helper::getData()); 
                        return;
                    } else {
                        Helper::sendResponse(404, ['error' => 'Method not found']);
                        return;
                    }
                } else {
                    Helper::sendResponse(404, ['error' => 'Controller not found']);
                    return;
                }
            } 
        }

        // If no route is found, send a 404 response
        Helper::sendResponse(404, ['error' => 'Route not found']);
    }

}