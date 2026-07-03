<?php
/**
 * File: admin/products/index.php
 * Purpose: Display paginated list of all products with search and sort
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/middleware.php';

$pageTitle = 'Products';
$breadcrumbHtml = breadcrumbs(['Products' => '']);

$productController = new ProductController();

// Pagination and search params
$page    = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$search  = trim($_GET['search'] ?? '');
$sortBy  = $_GET['sort_by'] ?? 'created_at';
$sortDir = $_GET['sort_dir'] ?? 'DESC';

// Toggle sort direction for column headers
function toggleDir(string $column, string $currentSort, string $currentDir): string
{
    if ($column === $currentSort) {
        return $currentDir === 'ASC' ? 'DESC' : 'ASC';
    }
    return 'DESC';
}

// Sort indicator
function sortIcon(string $column, string $currentSort, string $currentDir): string
{
    if ($column !== $currentSort) {
        return 'bi-arrow-down-up text-secondary';
    }
    return $currentDir === 'ASC' ? 'bi-sort-up' : 'bi-sort-down';
}

$result = $productController->index($page, $search, $sortBy, $sortDir);

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Products</h4>
        <span class="text-secondary" style="font-size:0.85rem;">
            <?= number_format($result['total']) ?> product<?= $result['total'] !== 1 ? 's' : '' ?> found
        </span>
    </div>
    <a href="<?= ADMIN_PATH ?>/products/create.php" class="btn btn-orange">
        <i class="bi bi-plus-lg me-1"></i> Add Product
    </a>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="" class="row g-2 align-items-end">
            <div class="col-12 col-md-8">
                <label class="form-label">Search Products</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search"
                           placeholder="Search by name, SKU, or description..."
                           value="<?= e($search) ?>">
                    <?php if ($search): ?>
                        <a href="<?= ADMIN_PATH ?>/products/index.php" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                    <button class="btn btn-orange" type="submit">Search</button>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label">Sort By</label>
                <select class="form-select" name="sort_by" onchange="this.form.submit()">
                    <option value="created_at" <?= $sortBy === 'created_at' ? 'selected' : '' ?>>Date</option>
                    <option value="name" <?= $sortBy === 'name' ? 'selected' : '' ?>>Name</option>
                    <option value="price" <?= $sortBy === 'price' ? 'selected' : '' ?>>Price</option>
                    <option value="stock_quantity" <?= $sortBy === 'stock_quantity' ? 'selected' : '' ?>>Stock</option>
                    <option value="status" <?= $sortBy === 'status' ? 'selected' : '' ?>>Status</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label">Direction</label>
                <select class="form-select" name="sort_dir" onchange="this.form.submit()">
                    <option value="DESC" <?= $sortDir === 'DESC' ? 'selected' : '' ?>>Descending</option>
                    <option value="ASC" <?= $sortDir === 'ASC' ? 'selected' : '' ?>>Ascending</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="card">
    <div class="card-body p-0">
        <?php if (empty($result['items'])): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size:3rem;color:var(--text-secondary);display:block;margin-bottom:1rem;"></i>
                <h5 class="text-secondary">No products found</h5>
                <?php if ($search): ?>
                    <p class="text-secondary mb-3">No results for "<?= e($search) ?>". Try a different search.</p>
                    <a href="<?= ADMIN_PATH ?>/products/index.php" class="btn btn-outline-secondary">Clear Search</a>
                <?php else: ?>
                    <p class="text-secondary mb-3">Get started by adding your first product.</p>
                    <a href="<?= ADMIN_PATH ?>/products/create.php" class="btn btn-orange">
                        <i class="bi bi-plus-lg me-1"></i> Add Product
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px;">Image</th>
                            <th>
                                <a href="?sort_by=name&sort_dir=<?= toggleDir('name', $sortBy, $sortDir) ?>&search=<?= e($search) ?>&page=<?= $page ?>"
                                   class="text-decoration-none text-secondary">
                                    Name <i class="bi <?= sortIcon('name', $sortBy, $sortDir) ?> ms-1"></i>
                                </a>
                            </th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>
                                <a href="?sort_by=price&sort_dir=<?= toggleDir('price', $sortBy, $sortDir) ?>&search=<?= e($search) ?>&page=<?= $page ?>"
                                   class="text-decoration-none text-secondary">
                                    Price <i class="bi <?= sortIcon('price', $sortBy, $sortDir) ?> ms-1"></i>
                                </a>
                            </th>
                            <th>
                                <a href="?sort_by=stock_quantity&sort_dir=<?= toggleDir('stock_quantity', $sortBy, $sortDir) ?>&search=<?= e($search) ?>&page=<?= $page ?>"
                                   class="text-decoration-none text-secondary">
                                    Stock <i class="bi <?= sortIcon('stock_quantity', $sortBy, $sortDir) ?> ms-1"></i>
                                </a>
                            </th>
                            <th>
                                <a href="?sort_by=status&sort_dir=<?= toggleDir('status', $sortBy, $sortDir) ?>&search=<?= e($search) ?>&page=<?= $page ?>"
                                   class="text-decoration-none text-secondary">
                                    Status <i class="bi <?= sortIcon('status', $sortBy, $sortDir) ?> ms-1"></i>
                                </a>
                            </th>
                            <th>
                                <a href="?sort_by=created_at&sort_dir=<?= toggleDir('created_at', $sortBy, $sortDir) ?>&search=<?= e($search) ?>&page=<?= $page ?>"
                                   class="text-decoration-none text-secondary">
                                    Date <i class="bi <?= sortIcon('created_at', $sortBy, $sortDir) ?> ms-1"></i>
                                </a>
                            </th>
                            <th style="width:100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result['items'] as $product): ?>
                            <tr>
                                <td>
                                    <?php if ($product['image']): ?>
                                        <img src="<?= PRODUCT_IMAGE_URL . '/' . e($product['image']) ?>"
                                             alt="<?= e($product['name']) ?>"
                                             class="product-thumb"
                                             loading="lazy">
                                    <?php else: ?>
                                        <div class="product-thumb-placeholder">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="font-weight:500;font-size:0.9rem;">
                                        <?= e(truncateText($product['name'], 50)) ?>
                                    </div>
                                </td>
                                <td><code style="font-size:0.8rem;"><?= e($product['sku']) ?></code></td>
                                <td style="font-size:0.85rem;"><?= e($product['category_name'] ?? 'N/A') ?></td>
                                <td class="fw-medium"><?= formatPrice((float) $product['price']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $product['stock_quantity'] > 0 ? 'success' : 'secondary' ?>">
                                        <?= (int) $product['stock_quantity'] ?>
                                    </span>
                                </td>
                                <td><?= statusBadge((int) $product['status']) ?></td>
                                <td style="font-size:0.8rem;color:var(--text-secondary);">
                                    <?= date(DATE_FORMAT, strtotime($product['created_at'])) ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="<?= ADMIN_PATH ?>/products/edit.php?id=<?= $product['id'] ?>"
                                           class="btn btn-sm btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="<?= ADMIN_PATH ?>/products/delete.php"
                                              onsubmit="return confirmDelete('Are you sure you want to delete \"<?= e($product['name']) ?>\"?')">
                                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
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

<!-- Pagination -->
<?php if ($result['totalPages'] > 1): ?>
<nav class="mt-4">
    <ul class="pagination justify-content-center">
        <li class="page-item <?= !$result['hasPrevious'] ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= e($search) ?>&sort_by=<?= $sortBy ?>&sort_dir=<?= $sortDir ?>">
                <i class="bi bi-chevron-left"></i>
            </a>
        </li>

        <?php
        $start = max(1, $page - 2);
        $end = min($result['totalPages'], $page + 2);
        if ($start > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=1&search=<?= e($search) ?>&sort_by=<?= $sortBy ?>&sort_dir=<?= $sortDir ?>">1</a>
            </li>
            <?php if ($start > 2): ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
            <?php endif; ?>
        <?php endif; ?>

        <?php for ($i = $start; $i <= $end; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&search=<?= e($search) ?>&sort_by=<?= $sortBy ?>&sort_dir=<?= $sortDir ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <?php if ($end < $result['totalPages']): ?>
            <?php if ($end < $result['totalPages'] - 1): ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
            <?php endif; ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $result['totalPages'] ?>&search=<?= e($search) ?>&sort_by=<?= $sortBy ?>&sort_dir=<?= $sortDir ?>">
                    <?= $result['totalPages'] ?>
                </a>
            </li>
        <?php endif; ?>

        <li class="page-item <?= !$result['hasNext'] ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= e($search) ?>&sort_by=<?= $sortBy ?>&sort_dir=<?= $sortDir ?>">
                <i class="bi bi-chevron-right"></i>
            </a>
        </li>
    </ul>
</nav>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
