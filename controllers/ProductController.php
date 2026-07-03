<?php
/**
 * File: controllers/ProductController.php
 * Purpose: Handles product CRUD operations
 */

class ProductController
{
    private Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    /**
     * Get paginated products
     */
    public function index(int $page, string $search, string $sortBy, string $sortDir): array
    {
        return $this->productModel->paginate($page, ITEMS_PER_PAGE, $search, $sortBy, $sortDir);
    }

    /**
     * Find product by ID
     */
    public function show(int $id): ?array
    {
        return $this->productModel->findById($id);
    }

    /**
     * Store a new product
     */
    public function store(array $data, array $file, string $csrfToken): array
    {
        if (!verifyCsrfToken($csrfToken)) {
            return ['success' => false, 'message' => 'Invalid security token.'];
        }

        $validator = new Validator();
        $validator
            ->required('name', 'Product name', $data['name'])
            ->required('price', 'Price', $data['price'])
            ->numeric('price', 'Price', $data['price'])
            ->min('price', 'Price', $data['price'], 0.01)
            ->required('category_id', 'Category', $data['category_id'])
            ->numeric('category_id', 'Category', $data['category_id'])
            ->required('sku', 'SKU', $data['sku'])
            ->sku('sku', 'SKU', $data['sku'])
            ->imageRequired($file, 'image');

        if (!$validator->passes()) {
            return ['success' => false, 'message' => $validator->firstError()];
        }

        // Check duplicate SKU
        if ($this->productModel->findBySku($data['sku'])) {
            return ['success' => false, 'message' => 'A product with this SKU already exists.'];
        }

        // Handle image upload
        $imageName = null;
        if ($file['error'] === UPLOAD_ERR_OK) {
            $imageName = $this->uploadImage($file);
            if ($imageName === false) {
                return ['success' => false, 'message' => 'Failed to upload image.'];
            }
        }

        $this->productModel->create([
            'name'           => trim($data['name']),
            'description'    => trim($data['description'] ?? ''),
            'price'          => $data['price'],
            'category_id'    => (int) $data['category_id'],
            'stock_quantity' => (int) ($data['stock_quantity'] ?? 0),
            'sku'            => strtoupper(trim($data['sku'])),
            'status'         => $data['status'] ?? 1,
            'image'          => $imageName,
        ]);

        return ['success' => true, 'message' => 'Product created successfully.'];
    }

    /**
     * Update an existing product
     */
    public function update(int $id, array $data, array $file, string $csrfToken): array
    {
        if (!verifyCsrfToken($csrfToken)) {
            return ['success' => false, 'message' => 'Invalid security token.'];
        }

        $product = $this->productModel->findById($id);
        if (!$product) {
            return ['success' => false, 'message' => 'Product not found.'];
        }

        $validator = new Validator();
        $validator
            ->required('name', 'Product name', $data['name'])
            ->required('price', 'Price', $data['price'])
            ->numeric('price', 'Price', $data['price'])
            ->min('price', 'Price', $data['price'], 0.01)
            ->required('category_id', 'Category', $data['category_id'])
            ->numeric('category_id', 'Category', $data['category_id'])
            ->required('sku', 'SKU', $data['sku'])
            ->sku('sku', 'SKU', $data['sku']);

        // If a new image is uploaded, validate it
        if ($file['error'] === UPLOAD_ERR_OK) {
            $validator->image($file, 'image');
        }

        if (!$validator->passes()) {
            return ['success' => false, 'message' => $validator->firstError()];
        }

        // Check duplicate SKU (excluding current product)
        if ($this->productModel->findBySku($data['sku'], $id)) {
            return ['success' => false, 'message' => 'A product with this SKU already exists.'];
        }

        // Handle image upload (replace)
        $imageName = $product['image'];
        if ($file['error'] === UPLOAD_ERR_OK) {
            // Delete old image
            if ($product['image']) {
                $this->deleteImage($product['image']);
            }
            $newImage = $this->uploadImage($file);
            if ($newImage === false) {
                return ['success' => false, 'message' => 'Failed to upload image.'];
            }
            $imageName = $newImage;
        }

        $updateData = [
            'name'           => trim($data['name']),
            'description'    => trim($data['description'] ?? ''),
            'price'          => $data['price'],
            'category_id'    => (int) $data['category_id'],
            'stock_quantity' => (int) ($data['stock_quantity'] ?? 0),
            'sku'            => strtoupper(trim($data['sku'])),
            'status'         => $data['status'] ?? 1,
        ];

        // Only include image if changed
        if ($imageName !== $product['image']) {
            $updateData['image'] = $imageName;
        }

        $this->productModel->update($id, $updateData);

        return ['success' => true, 'message' => 'Product updated successfully.'];
    }

    /**
     * Delete a product and its image
     */
    public function destroy(int $id, string $csrfToken): array
    {
        if (!verifyCsrfToken($csrfToken)) {
            return ['success' => false, 'message' => 'Invalid security token.'];
        }

        $product = $this->productModel->findById($id);
        if (!$product) {
            return ['success' => false, 'message' => 'Product not found.'];
        }

        // Delete associated image
        if ($product['image']) {
            $this->deleteImage($product['image']);
        }

        $this->productModel->delete($id);
        return ['success' => true, 'message' => 'Product deleted successfully.'];
    }

    /**
     * Upload product image
     */
    private function uploadImage(array $file): string|false
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $ext = getExtensionFromMime($mimeType);
        $filename = uniqueFilename($ext);
        $destPath = PRODUCT_IMAGE_PATH . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            return false;
        }

        return $filename;
    }

    /**
     * Delete image from server
     */
    private function deleteImage(string $filename): bool
    {
        $path = PRODUCT_IMAGE_PATH . '/' . $filename;
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }
}
