<?php
namespace App\Core;
class Router {
    private $routes = [];

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path from URI
        $basePath = '/phpProject/public';
        $requestPath = str_replace($basePath, '', $requestUri);
        
        foreach ($this->routes as $route) {
            // Convert route pattern to regex
            $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = "#^" . $pattern . "$#";
            
            if ($route['method'] === $requestMethod && preg_match($pattern, $requestPath, $matches)) {
                // Extract parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                list($controller, $method) = explode('@', $route['handler']);
                $controllerClass = "\\App\\Controllers\\" . $controller;
                
                if (!class_exists($controllerClass)) {
                    throw new \Exception("Controller {$controller} not found");
                }
                
                $controllerInstance = new $controllerClass();
                return $controllerInstance->$method(...array_values($params));
            }
        }
        
        // No route found
        header("HTTP/1.0 404 Not Found");
        include __DIR__ . "/../../views/errors/404.php";
    }
}