<?php
/**
 * File: admin/categories/index.php
 * Purpose: List all categories with edit/delete actions
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/middleware.php';

$pageTitle = 'Categories';
$breadcrumbHtml = breadcrumbs(['Categories' => '']);

$categoryController = new CategoryController();
$categories = $categoryController->index('created_at', 'DESC');

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Categories</h4>
        <span class="text-secondary" style="font-size:0.85rem;">
            <?= count($categories) ?> categor<?= count($categories) !== 1 ? 'ies' : 'y' ?> found
        </span>
    </div>
    <a href="<?= ADMIN_PATH ?>/categories/create.php" class="btn btn-orange">
        <i class="bi bi-plus-lg me-1"></i> Add Category
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($categories)): ?>
            <div class="text-center py-5">
                <i class="bi bi-grid" style="font-size:3rem;color:var(--text-secondary);display:block;margin-bottom:1rem;"></i>
                <h5 class="text-secondary">No categories yet</h5>
                <p class="text-secondary mb-3">Create your first category to organize products.</p>
                <a href="<?= ADMIN_PATH ?>/categories/create.php" class="btn btn-orange">
                    <i class="bi bi-plus-lg me-1"></i> Add Category
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th style="width:100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $index => $cat): ?>
                            <tr>
                                <td class="text-secondary"><?= $cat['id'] ?></td>
                                <td>
                                    <span style="font-weight:500;"><?= e($cat['name']) ?></span>
                                </td>
                                <td><code style="font-size:0.8rem;"><?= e($cat['slug']) ?></code></td>
                                <td style="color:var(--text-secondary);font-size:0.85rem;max-width:250px;">
                                    <?= $cat['description'] ? e(truncateText($cat['description'], 60)) : '<span class="text-secondary">—</span>' ?>
                                </td>
                                <td><?= statusBadge((int) $cat['status']) ?></td>
                                <td style="font-size:0.85rem;color:var(--text-secondary);">
                                    <?= date(DATE_FORMAT, strtotime($cat['created_at'])) ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="<?= ADMIN_PATH ?>/categories/edit.php?id=<?= $cat['id'] ?>"
                                           class="btn btn-sm btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="<?= ADMIN_PATH ?>/categories/delete.php"
                                              onsubmit="return confirmDelete('Are you sure you want to delete the category \"<?= e($cat['name']) ?>\"? Products in this category will also be deleted.')">
                                            <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
