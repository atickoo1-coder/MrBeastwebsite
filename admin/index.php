<?php
/**
 * File: admin/index.php
 * Purpose: Admin panel entry point - redirects to dashboard
 */

require_once __DIR__ . '/../includes/config.php';

// Redirect to dashboard
redirect(ADMIN_PATH . '/dashboard.php');
