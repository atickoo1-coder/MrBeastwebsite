<?php
require_once __DIR__ . '/../includes/frontend-config.php';

Session::destroy();
Session::setFlash('success', 'You have been logged out successfully.');
header('Location: ' . APP_URL . '/index.html');
exit;
