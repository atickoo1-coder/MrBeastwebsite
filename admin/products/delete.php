<?php
/**
 * File: admin/products/delete.php
 * Purpose: Delete a product and its associated image
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/middleware.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(ADMIN_PATH . '/products/index.php');
}

$productController = new ProductController();
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$csrfToken = $_POST['csrf_token'] ?? '';

$result = $productController->destroy($id, $csrfToken);

if ($result['success']) {
    Session::setFlash('success', $result['message']);
} else {
    Session::setFlash('error', $result['message']);
}

redirect(ADMIN_PATH . '/products/index.php');
