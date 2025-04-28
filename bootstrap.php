<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configuration
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

// Load core classes
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ElecteurController.php';
require_once __DIR__ . '/controllers/ElectionController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/CandidatController.php';

require_once __DIR__ . '/core/Router.php';

// Load helpers
require_once __DIR__ . '/helpers/functions.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');
error_log("Request URL: " . $_SERVER['REQUEST_URI']);
