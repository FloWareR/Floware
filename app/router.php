<?php 

namespace App;

use App\Controllers\Helper;

class Router {
    // Store routes in an array
    private $routes = [];

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

        if(!isset($path[1])) {
            $path[1] = 'views';
        }
        if(!isset($path[2])) {
            $path[2] = 'index';
        }

        // Loop through the routes array
        foreach ($this->routes as $route) {            
            if ($route['method'] === strtoupper($requestMethod) && $route['path'] === $path[1] && $route['action'] === $path[2]) {
                $controller = 'App\\Controllers\\' . $route['controller'];
                if(class_exists($controller)) {
                    $controller = new $controller();
                    if(method_exists($controller, $path[2])){ 
                        $controller->{$route['action']}($route['clearance'], $data = Helper::getData());
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