<?php
/**
 * File: includes/middleware.php
 * Purpose: Authentication middleware - protects admin routes
 * Include this file at the top of every admin page that requires login
 */

// Ensure config is loaded
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/config.php';
}

// Check if user is logged in
if (!AuthController::isLoggedIn()) {
    Session::setFlash('warning', 'Please login to access the admin panel.');
    redirect(ADMIN_PATH . '/auth/login.php');
    exit;
}
