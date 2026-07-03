<?php
/**
 * File: admin/auth/logout.php
 * Purpose: Log out admin user and redirect to login page
 */

require_once __DIR__ . '/../../includes/config.php';

AuthController::logout();
Session::setFlash('info', 'You have been logged out successfully.');
redirect(ADMIN_PATH . '/auth/login.php');
