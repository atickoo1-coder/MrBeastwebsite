<?php
/**
 * File: admin/categories/create.php
 * Purpose: Add a new product category
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/middleware.php';

$pageTitle = 'Add Category';
$breadcrumbHtml = breadcrumbs([
    'Categories' => ADMIN_PATH . '/categories/index.php',
    'Add Category' => '',
]);

$categoryController = new CategoryController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $categoryController->store($_POST, $_POST['csrf_token'] ?? '');

    if ($result['success']) {
        Session::setFlash('success', $result['message']);
        redirect(ADMIN_PATH . '/categories/index.php');
    } else {
        Session::setFlash('error', $result['message']);
    }
}

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Add Category</h4>
        <span class="text-secondary" style="font-size:0.85rem;">Create a new product category</span>
    </div>
    <a href="<?= ADMIN_PATH ?>/categories/index.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Categories
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="" class="row g-3">
            <?= csrfField() ?>

            <div class="col-12 col-md-8">
                <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name"
                       value="<?= e($_POST['name'] ?? '') ?>" required maxlength="100"
                       placeholder="e.g. T-Shirts, Hoodies, Accessories">
                <div style="font-size:0.75rem;color:var(--text-secondary);margin-top:0.25rem;">
                    A URL-friendly slug will be generated automatically.
                </div>
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label">Status</label>
                <div class="d-flex gap-3 pt-1">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status"
                               id="statusActive" value="1"
                               <?= (isset($_POST['status']) && $_POST['status'] == 1) || !isset($_POST['status']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="statusActive">Active</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status"
                               id="statusInactive" value="0"
                               <?= (isset($_POST['status']) && $_POST['status'] == 0) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="statusInactive">Inactive</label>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"
                          placeholder="Enter category description (optional)"><?= e($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="col-12 mt-4 pt-3 border-top border-secondary">
                <button type="submit" class="btn btn-orange">
                    <i class="bi bi-check-lg me-1"></i> Create Category
                </button>
                <a href="<?= ADMIN_PATH ?>/categories/index.php" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
