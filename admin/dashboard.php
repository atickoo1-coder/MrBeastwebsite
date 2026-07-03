<?php
/**
 * File: admin/dashboard.php
 * Purpose: Admin dashboard showing overview statistics
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/middleware.php';

$pageTitle = 'Dashboard';
$breadcrumbHtml = breadcrumbs(['Dashboard' => '']);

$productModel = new Product();
$categoryModel = new Category();

$totalProducts    = $productModel->count();
$activeProducts   = $productModel->countByStatus(1);
$inactiveProducts = $productModel->countByStatus(0);
$totalCategories  = $categoryModel->count();
$latestProducts   = $productModel->getLatest(5);

require_once __DIR__ . '/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Dashboard</h4>
    <span class="text-secondary" style="font-size:0.85rem;">
        <i class="bi bi-calendar3 me-1"></i> <?= date('l, F j, Y') ?>
    </span>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-box-seam"></i></div>
            <div class="stat-value"><?= number_format($totalProducts) ?></div>
            <div class="stat-label">Total Products</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
            <div class="stat-value"><?= number_format($activeProducts) ?></div>
            <div class="stat-label">Active Products</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-x-circle"></i></div>
            <div class="stat-value"><?= number_format($inactiveProducts) ?></div>
            <div class="stat-label">Inactive Products</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-grid"></i></div>
            <div class="stat-value"><?= number_format($totalCategories) ?></div>
            <div class="stat-label">Categories</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Latest Products -->
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Latest Products</span>
                <a href="<?= ADMIN_PATH ?>/products/index.php" class="btn btn-sm btn-outline-orange">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($latestProducts)): ?>
                    <div class="text-center py-4 text-secondary">
                        <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:0.5rem;"></i>
                        No products yet.
                        <a href="<?= ADMIN_PATH ?>/products/create.php" class="d-block mt-1">Add your first product</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($latestProducts as $product): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if ($product['image']): ?>
                                                    <img src="<?= PRODUCT_IMAGE_URL . '/' . e($product['image']) ?>"
                                                         alt="<?= e($product['name']) ?>"
                                                         class="product-thumb">
                                                <?php else: ?>
                                                    <div class="product-thumb-placeholder">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <div style="font-weight:500;font-size:0.9rem;">
                                                        <?= e(truncateText($product['name'], 40)) ?>
                                                    </div>
                                                    <div style="font-size:0.75rem;color:var(--text-secondary);">
                                                        SKU: <?= e($product['sku']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= e($product['category_name'] ?? 'N/A') ?></td>
                                        <td><?= formatPrice((float) $product['price']) ?></td>
                                        <td><?= statusBadge((int) $product['status']) ?></td>
                                        <td style="font-size:0.85rem;color:var(--text-secondary);">
                                            <?= date(DATE_FORMAT, strtotime($product['created_at'])) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-lightning me-2"></i>Quick Actions
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= ADMIN_PATH ?>/products/create.php" class="btn btn-orange">
                        <i class="bi bi-plus-lg me-1"></i> Add New Product
                    </a>
                    <a href="<?= ADMIN_PATH ?>/categories/create.php" class="btn btn-outline-orange">
                        <i class="bi bi-plus-lg me-1"></i> Add New Category
                    </a>
                    <a href="<?= ADMIN_PATH ?>/products/index.php" class="btn btn-outline-secondary text-secondary border-secondary">
                        <i class="bi bi-box-seam me-1"></i> Manage Products
                    </a>
                    <a href="<?= ADMIN_PATH ?>/categories/index.php" class="btn btn-outline-secondary text-secondary border-secondary">
                        <i class="bi bi-grid me-1"></i> Manage Categories
                    </a>
                </div>
            </div>
        </div>

        <!-- Store Summary -->
        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>Store Summary
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Products</span>
                    <span class="fw-bold"><?= $totalProducts ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Categories</span>
                    <span class="fw-bold"><?= $totalCategories ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Active</span>
                    <span class="fw-bold text-success"><?= $activeProducts ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-secondary">Inactive</span>
                    <span class="fw-bold text-secondary"><?= $inactiveProducts ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
