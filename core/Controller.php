<?php

class Controller {
    public function __construct() {
        // Base constructor initialization if needed
    }

    protected function view($view, $data = []) {
        // Extract data to make variables available in view
        extract($data);
        
        // Include the view file
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            throw new Exception("View {$view} not found");
        }
    }

    protected function redirect($url) {
        header("Location: " . BASE_URL . $url);
        exit();
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}