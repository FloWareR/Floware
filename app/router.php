<?php 

namespace App;

class Router {
    // Store routes in an array
    private $routes = [];

    // Add a route to the routes array
    public function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    // Enroute the request to the appropriate controller and action
    public function enroute($requestUri, $requestMethod) {

        // Parse the request URI
        $path = parse_url($requestUri, PHP_URL_PATH);
        $path = trim($path, '/');
        $path = explode('/', $path);

        // Loop through the routes array
        foreach ($this->routes as $route) {            
            if ($route['method'] === strtoupper($requestMethod) && $route['path'] === $path[1]) {
                $controller = 'App\\Controllers\\' . $route['controller'];
                if(class_exists($controller)) {
                    $controller = new $controller();
                    if(method_exists($controller, $path[2])){ 
                        $controller->{$route['action']}();
                        return;
                    } else {
                        $this->sendResponse(404, ['error' => 'Method not found']);
                        return;
                    }
                } else {
                    $this->sendResponse(404, ['error' => 'Controller not found']);
                    return;
                }
            } 
        }

        // If no route is found, send a 404 response
        http_response_code(404);
        header('Content-Type: application/json');
        $this->sendResponse(404, ['error' => 'Route not found']);
    }


    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}