<?php
/**
 * File: includes/config.php
 * Purpose: Bootstrap file - loads all configs, helpers, and session
 * Include this at the top of every admin page
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load application config
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

// Load helpers
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/validation.php';

// Load models
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Product.php';

// Load controllers
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/CategoryController.php';
require_once __DIR__ . '/../controllers/ProductController.php';

// Set timezone
date_default_timezone_set('America/New_York');

// Error reporting based on environment
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
