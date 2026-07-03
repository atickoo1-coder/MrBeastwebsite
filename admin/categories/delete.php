<?php
/**
 * File: admin/categories/delete.php
 * Purpose: Delete a category (cascades to products)
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/middleware.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(ADMIN_PATH . '/categories/index.php');
}

$categoryController = new CategoryController();
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$csrfToken = $_POST['csrf_token'] ?? '';

$result = $categoryController->destroy($id, $csrfToken);

if ($result['success']) {
    Session::setFlash('success', $result['message']);
} else {
    Session::setFlash('error', $result['message']);
}

redirect(ADMIN_PATH . '/categories/index.php');
