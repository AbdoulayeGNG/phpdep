<?php
class Router {
    private $routes = [];
    private $protectedRoutes = ['dashboard', 'elections', 'candidats', 'users', 'rapports'];
    private $publicRoutes = ['auth/login', 'auth/register'];

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch() {
        try {
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            
            // Debug logging
            error_log("Processing request: " . $requestMethod . " " . $requestUri);
            
            // Remove base path from URI
            $basePath = '/phpProject/public';
            $requestPath = trim(str_replace($basePath, '', $requestUri), '/');
            
            error_log("Cleaned path: " . $requestPath);

            // Handle root path (/)
            if (empty($requestPath)) {
                error_log("Root path detected, redirecting to login");
                header('Location: ' . BASE_URL . '/public/auth/login');
                exit();
            }

            // Check authentication for protected routes
            if (!$this->isPublicRoute($requestPath)) {
                if (!isset($_SESSION['user_id'])) {
                    error_log("Unauthorized access attempt to: " . $requestPath);
                    header('Location: ' . BASE_URL . '/public/auth/login');
                    exit();
                }
                
                // Check role-based access
                if (isset($_SESSION['user_role'])) {
                    if ($_SESSION['user_role'] !== 'admin' && $this->isAdminRoute($requestPath)) {
                        error_log("Non-admin tried to access: " . $requestPath);
                        $this->showError("Access denied");
                        exit();
                    }
                }
            }

            // Match and execute route
            foreach ($this->routes as $route) {
                if ($route['method'] === $requestMethod && $this->matchRoute($route['path'], $requestPath)) {
                    error_log("Route matched: " . $route['path']);
                    list($controller, $method) = explode('@', $route['handler']);
                    return $this->executeRoute($controller, $method);
                }
            }
            
            // No route found
            error_log("No route found for: " . $requestPath);
            $this->show404();

        } catch (Exception $e) {
            error_log("Router Error: " . $e->getMessage());
            $this->showError($e->getMessage());
        }
    }

    private function isProtectedRoute($path) {
        foreach ($this->protectedRoutes as $route) {
            if (strpos($path, $route) === 0) {
                return true;
            }
        }
        return false;
    }

    private function isPublicRoute($path) {
        foreach ($this->publicRoutes as $route) {
            if (strpos($path, $route) === 0) {
                return true;
            }
        }
        return false;
    }

    private function isAdminRoute($path) {
        return strpos($path, 'admin') === 0 || 
               strpos($path, 'rapports') === 0 || 
               strpos($path, 'users') === 0;
    }

    private function matchRoute($routePath, $requestPath) {
        $routePath = trim($routePath, '/');
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $routePath);
        $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
        return preg_match($pattern, $requestPath);
    }

    private function executeRoute($controller, $method) {
        if (!class_exists($controller)) {
            throw new Exception("Controller not found: {$controller}");
        }

        $controllerInstance = new $controller();
        if (!method_exists($controllerInstance, $method)) {
            throw new Exception("Method not found: {$method}");
        }

        return $controllerInstance->$method();
    }

    private function show404() {
        header("HTTP/1.0 404 Not Found");
        require_once dirname(__DIR__) . '/views/404.php';
        exit();
    }

    private function showError($message) {
        header("HTTP/1.0 500 Internal Server Error");
        require_once dirname(__DIR__) . '/views/error.php';
        exit();
    }
}