<?php
/**
 * File: controllers/CategoryController.php
 * Purpose: Handles category CRUD operations
 */

class CategoryController
{
    private Category $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    /**
     * Get all categories
     */
    public function index(string $orderBy = 'created_at', string $direction = 'DESC'): array
    {
        return $this->categoryModel->all($orderBy, $direction);
    }

    /**
     * Get active categories for dropdowns
     */
    public function getActive(): array
    {
        return $this->categoryModel->getActive();
    }

    /**
     * Store a new category
     */
    public function store(array $data, string $csrfToken): array
    {
        if (!verifyCsrfToken($csrfToken)) {
            return ['success' => false, 'message' => 'Invalid security token.'];
        }

        $validator = new Validator();
        $validator
            ->required('name', 'Category name', $data['name'])
            ->maxLength('name', 'Category name', $data['name'], 100);

        if (!$validator->passes()) {
            return ['success' => false, 'message' => $validator->firstError()];
        }

        // Generate slug
        $slug = slugify($data['name']);

        // Check for duplicate slug
        $existing = $this->categoryModel->findBySlug($slug);
        if ($existing) {
            $slug = $slug . '-' . time();
        }

        $this->categoryModel->create([
            'name'        => trim($data['name']),
            'slug'        => $slug,
            'description' => trim($data['description'] ?? ''),
            'status'      => $data['status'] ?? 1,
        ]);

        return ['success' => true, 'message' => 'Category created successfully.'];
    }

    /**
     * Find category by ID
     */
    public function show(int $id): ?array
    {
        return $this->categoryModel->findById($id);
    }

    /**
     * Update an existing category
     */
    public function update(int $id, array $data, string $csrfToken): array
    {
        if (!verifyCsrfToken($csrfToken)) {
            return ['success' => false, 'message' => 'Invalid security token.'];
        }

        // Check category exists
        $category = $this->categoryModel->findById($id);
        if (!$category) {
            return ['success' => false, 'message' => 'Category not found.'];
        }

        $validator = new Validator();
        $validator
            ->required('name', 'Category name', $data['name'])
            ->maxLength('name', 'Category name', $data['name'], 100);

        if (!$validator->passes()) {
            return ['success' => false, 'message' => $validator->firstError()];
        }

        $slug = slugify($data['name']);
        $existing = $this->categoryModel->findBySlug($slug);
        if ($existing && $existing['id'] !== $id) {
            $slug = $slug . '-' . time();
        }

        $this->categoryModel->update($id, [
            'name'        => trim($data['name']),
            'slug'        => $slug,
            'description' => trim($data['description'] ?? ''),
            'status'      => $data['status'] ?? 1,
        ]);

        return ['success' => true, 'message' => 'Category updated successfully.'];
    }

    /**
     * Delete a category
     */
    public function destroy(int $id, string $csrfToken): array
    {
        if (!verifyCsrfToken($csrfToken)) {
            return ['success' => false, 'message' => 'Invalid security token.'];
        }

        $category = $this->categoryModel->findById($id);
        if (!$category) {
            return ['success' => false, 'message' => 'Category not found.'];
        }

        $this->categoryModel->delete($id);
        return ['success' => true, 'message' => 'Category deleted successfully.'];
    }
}
