<?php
/**
 * File: admin/categories/edit.php
 * Purpose: Edit an existing category
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/middleware.php';

$pageTitle = 'Edit Category';
$breadcrumbHtml = breadcrumbs([
    'Categories' => ADMIN_PATH . '/categories/index.php',
    'Edit Category' => '',
]);

$categoryController = new CategoryController();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$category = $categoryController->show($id);

if (!$category) {
    Session::setFlash('error', 'Category not found.');
    redirect(ADMIN_PATH . '/categories/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $categoryController->update($id, $_POST, $_POST['csrf_token'] ?? '');

    if ($result['success']) {
        Session::setFlash('success', $result['message']);
        redirect(ADMIN_PATH . '/categories/index.php');
    } else {
        Session::setFlash('error', $result['message']);
        $category = $categoryController->show($id);
    }
}

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Edit Category</h4>
        <span class="text-secondary" style="font-size:0.85rem;">
            Editing: <?= e($category['name']) ?>
        </span>
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
                       value="<?= e($_POST['name'] ?? $category['name']) ?>" required maxlength="100"
                       placeholder="e.g. T-Shirts, Hoodies, Accessories">
                <div style="font-size:0.75rem;color:var(--text-secondary);margin-top:0.25rem;">
                    Slug: <code><?= e($category['slug']) ?></code>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label">Status</label>
                <div class="d-flex gap-3 pt-1">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status"
                               id="statusActive" value="1"
                               <?= ((int) ($_POST['status'] ?? $category['status']) === 1) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="statusActive">Active</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status"
                               id="statusInactive" value="0"
                               <?= ((int) ($_POST['status'] ?? $category['status']) === 0) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="statusInactive">Inactive</label>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"
                          placeholder="Enter category description (optional)"><?= e($_POST['description'] ?? $category['description']) ?></textarea>
            </div>

            <div class="col-12 mt-4 pt-3 border-top border-secondary">
                <button type="submit" class="btn btn-orange">
                    <i class="bi bi-check-lg me-1"></i> Update Category
                </button>
                <a href="<?= ADMIN_PATH ?>/categories/index.php" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
