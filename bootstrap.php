<?php
session_start();

// Load configuration
require_once __DIR__ . '/config/config.php';

// Load core classes
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';

// Load controllers
require_once __DIR__ . '/controllers/ElecteurController.php';
require_once __DIR__ . '/controllers/ParametreController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ElectionController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/CandidatController.php';
require_once __DIR__ . '/controllers/ProfileController.php';


// Load helpers
require_once __DIR__ . '/helpers/functions.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');
error_log("Request URL: " . $_SERVER['REQUEST_URI']);
