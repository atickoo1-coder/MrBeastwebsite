<?php
/**
 * File: admin/products/create.php
 * Purpose: Add a new product with image upload
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/middleware.php';

$pageTitle = 'Add Product';
$breadcrumbHtml = breadcrumbs([
    'Products' => ADMIN_PATH . '/products/index.php',
    'Add Product' => '',
]);

$productController = new ProductController();
$categoryController = new CategoryController();
$categories = $categoryController->getActive();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $productController->store($_POST, $_FILES['image'] ?? [], $_POST['csrf_token'] ?? '');

    if ($result['success']) {
        Session::setFlash('success', $result['message']);
        redirect(ADMIN_PATH . '/products/index.php');
    } else {
        Session::setFlash('error', $result['message']);
    }
}

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Add Product</h4>
        <span class="text-secondary" style="font-size:0.85rem;">Create a new product in your inventory</span>
    </div>
    <a href="<?= ADMIN_PATH ?>/products/index.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Products
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="" enctype="multipart/form-data" class="row g-3">
            <?= csrfField() ?>

            <!-- Left Column -->
            <div class="col-12 col-lg-8">
                <div class="row g-3">
                    <!-- Product Name -->
                    <div class="col-12">
                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="<?= e($_POST['name'] ?? '') ?>" required maxlength="255"
                               placeholder="Enter product name">
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5"
                                  placeholder="Enter product description"><?= e($_POST['description'] ?? '') ?></textarea>
                    </div>

                    <!-- Price & SKU -->
                    <div class="col-md-6">
                        <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="price" name="price"
                                   value="<?= e($_POST['price'] ?? '') ?>" required
                                   step="0.01" min="0.01" placeholder="0.00">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="sku" name="sku"
                               value="<?= e($_POST['sku'] ?? '') ?>" required maxlength="100"
                               placeholder="e.g. MB-TEE-001">
                        <div style="font-size:0.75rem;color:var(--text-secondary);margin-top:0.25rem;">
                            Letters, numbers, and hyphens only. Must be unique.
                        </div>
                    </div>

                    <!-- Category & Stock -->
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"
                                    <?= (isset($_POST['category_id']) && (int) $_POST['category_id'] === (int) $cat['id']) ? 'selected' : '' ?>>
                                    <?= e($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="stock_quantity" class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity"
                               value="<?= (int) ($_POST['stock_quantity'] ?? 0) ?>" min="0"
                               placeholder="0">
                    </div>

                    <!-- Status -->
                    <div class="col-12">
                        <label class="form-label">Status</label>
                        <div class="d-flex gap-3">
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
                </div>
            </div>

            <!-- Right Column - Image Upload -->
            <div class="col-12 col-lg-4">
                <div class="card bg-transparent border">
                    <div class="card-body text-center">
                        <label class="form-label d-block">Product Image <span class="text-danger">*</span></label>

                        <div id="imagePreviewContainer" class="mb-3"
                             style="display:none;width:200px;height:200px;margin:0 auto;border:2px dashed var(--border-color);border-radius:8px;overflow:hidden;">
                            <img id="imagePreview" src="" alt="Preview" style="width:100%;height:100%;object-fit:cover;">
                        </div>

                        <div id="imagePlaceholder" style="width:200px;height:200px;margin:0 auto;border:2px dashed var(--border-color);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-direction:column;color:var(--text-secondary);">
                            <i class="bi bi-image" style="font-size:2.5rem;margin-bottom:0.5rem;"></i>
                            <span style="font-size:0.85rem;">Click to upload image</span>
                            <span style="font-size:0.7rem;">JPG, PNG, WebP, GIF (max 2MB)</span>
                        </div>

                        <input type="file" class="form-control mt-2" id="image" name="image"
                               accept="image/jpeg,image/png,image/webp,image/gif"
                               onchange="previewImage(event)">

                        <div id="imageError" class="text-danger mt-2" style="font-size:0.85rem;display:none;"></div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="col-12 mt-4 pt-3 border-top border-secondary">
                <button type="submit" class="btn btn-orange">
                    <i class="bi bi-check-lg me-1"></i> Create Product
                </button>
                <a href="<?= ADMIN_PATH ?>/products/index.php" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    const container = document.getElementById('imagePreviewContainer');
    const placeholder = document.getElementById('imagePlaceholder');
    const error = document.getElementById('imageError');

    error.style.display = 'none';

    if (!file) {
        container.style.display = 'none';
        placeholder.style.display = 'flex';
        return;
    }

    // Validate file size
    if (file.size > <?= MAX_FILE_SIZE ?>) {
        error.textContent = 'Image must be less than <?= MAX_FILE_SIZE / 1024 / 1024 ?>MB.';
        error.style.display = 'block';
        event.target.value = '';
        container.style.display = 'none';
        placeholder.style.display = 'flex';
        return;
    }

    // Validate type
    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!allowedTypes.includes(file.type)) {
        error.textContent = 'Only JPG, PNG, WebP, and GIF images are allowed.';
        error.style.display = 'block';
        event.target.value = '';
        container.style.display = 'none';
        placeholder.style.display = 'flex';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        preview.src = e.target.result;
        container.style.display = 'block';
        placeholder.style.display = 'none';
    };
    reader.readAsDataURL(file);
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
