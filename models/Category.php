<?php
/**
 * File: models/Category.php
 * Purpose: Category model - handles all category database operations
 */

class Category
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all categories
     */
    public function all(string $orderBy = 'created_at', string $direction = 'DESC'): array
    {
        $allowedOrders = ['id', 'name', 'status', 'created_at'];
        $allowedDirections = ['ASC', 'DESC'];

        $orderBy = in_array($orderBy, $allowedOrders) ? $orderBy : 'created_at';
        $direction = in_array(strtoupper($direction), $allowedDirections) ? strtoupper($direction) : 'DESC';

        $stmt = $this->db->query("SELECT * FROM categories ORDER BY {$orderBy} {$direction}");
        return $stmt->fetchAll();
    }

    /**
     * Get only active categories
     */
    public function getActive(): array
    {
        $stmt = $this->db->query(
            'SELECT id, name, slug FROM categories WHERE status = 1 ORDER BY name ASC'
        );
        return $stmt->fetchAll();
    }

    /**
     * Find category by ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM categories WHERE id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Find category by slug
     */
    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM categories WHERE slug = :slug LIMIT 1'
        );
        $stmt->execute([':slug' => $slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Create a new category
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO categories (name, slug, description, status) VALUES (:name, :slug, :description, :status)'
        );
        $stmt->execute([
            ':name'        => $data['name'],
            ':slug'        => $data['slug'],
            ':description' => $data['description'] ?? null,
            ':status'      => $data['status'] ?? 1,
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Update an existing category
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE categories SET name = :name, slug = :slug, description = :description, status = :status WHERE id = :id'
        );
        return $stmt->execute([
            ':id'          => $id,
            ':name'        => $data['name'],
            ':slug'        => $data['slug'],
            ':description' => $data['description'] ?? null,
            ':status'      => $data['status'] ?? 1,
        ]);
    }

    /**
     * Delete a category
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Count total categories
     */
    public function count(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) as total FROM categories');
        return (int) $stmt->fetch()['total'];
    }
}
