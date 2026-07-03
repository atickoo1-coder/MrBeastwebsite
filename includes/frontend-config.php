<?php
/**
 * Bootstrap file for frontend pages
 * Include this at the top of every frontend PHP page
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/functions.php';

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../models/',
        __DIR__ . '/../controllers/',
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

date_default_timezone_set('America/New_York');

if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
