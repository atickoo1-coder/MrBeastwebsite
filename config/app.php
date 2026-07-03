<?php
/**
 * File: config/app.php
 * Purpose: Application-wide configuration constants
 */

// Site configuration
define('APP_NAME', 'MrBeast Store Admin');
define('APP_URL', 'http://localhost/MrBeastwebsite');
define('APP_ENV', 'development'); // 'development' or 'production'

// Admin panel paths
define('ADMIN_PATH', APP_URL . '/admin');
define('UPLOAD_PATH', __DIR__ . '/../uploads');
define('PRODUCT_IMAGE_PATH', UPLOAD_PATH . '/products');
define('PRODUCT_IMAGE_URL', APP_URL . '/uploads/products');

// File upload configuration
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

// Pagination
define('ITEMS_PER_PAGE', 10);

// Date format
define('DATE_FORMAT', 'M d, Y');
define('DATETIME_FORMAT', 'M d, Y h:i A');
