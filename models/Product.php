<?php
/**
 * File: models/Product.php
 * Purpose: Product model - handles all product database operations
 */

class Product
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get paginated products with optional search and sort
     */
    public function paginate(
        int $page = 1,
        int $perPage = ITEMS_PER_PAGE,
        string $search = '',
        string $sortBy = 'created_at',
        string $sortDir = 'DESC'
    ): array {
        $allowedSorts = ['id', 'name', 'price', 'stock_quantity', 'status', 'created_at'];
        $allowedDirs  = ['ASC', 'DESC'];

        $sortBy = in_array($sortBy, $allowedSorts) ? $sortBy : 'created_at';
        $sortDir = in_array(strtoupper($sortDir), $allowedDirs) ? strtoupper($sortDir) : 'DESC';

        $offset = ($page - 1) * $perPage;

        $where = '';
        $params = [];

        if (!empty($search)) {
            $where = 'WHERE p.name LIKE :search OR p.sku LIKE :search2 OR p.description LIKE :search3';
            $params[':search'] = "%{$search}%";
            $params[':search2'] = "%{$search}%";
            $params[':search3'] = "%{$search}%";
        }

        // Count total
        $countSql = "SELECT COUNT(*) as total FROM products p {$where}";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int) $countStmt->fetch()['total'];

        // Fetch page
        $sql = "
            SELECT p.*, c.name as category_name, c.slug as category_slug
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            {$where}
            ORDER BY p.{$sortBy} {$sortDir}
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll();

        $totalPages = (int) ceil($total / $perPage);

        return [
            'items'       => $items,
            'total'       => $total,
            'page'        => $page,
            'perPage'     => $perPage,
            'totalPages'  => $totalPages,
            'hasPrevious' => $page > 1,
            'hasNext'     => $page < $totalPages,
        ];
    }

    /**
     * Find product by ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, c.name as category_name
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Find product by SKU
     */
    public function findBySku(string $sku, ?int $excludeId = null): ?array
    {
        $sql = 'SELECT id FROM products WHERE sku = :sku';
        $params = [':sku' => $sku];

        if ($excludeId !== null) {
            $sql .= ' AND id != :exclude_id';
            $params[':exclude_id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Create a new product
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO products (name, description, price, category_id, stock_quantity, sku, status, image)
             VALUES (:name, :description, :price, :category_id, :stock_quantity, :sku, :status, :image)'
        );
        $stmt->execute([
            ':name'           => $data['name'],
            ':description'    => $data['description'] ?? null,
            ':price'          => $data['price'],
            ':category_id'    => $data['category_id'],
            ':stock_quantity' => $data['stock_quantity'] ?? 0,
            ':sku'            => $data['sku'],
            ':status'         => $data['status'] ?? 1,
            ':image'          => $data['image'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Update an existing product
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE products SET
                name = :name, description = :description, price = :price,
                category_id = :category_id, stock_quantity = :stock_quantity,
                sku = :sku, status = :status' .
                (isset($data['image']) ? ', image = :image' : '') .
            ' WHERE id = :id'
        );

        $params = [
            ':id'             => $id,
            ':name'           => $data['name'],
            ':description'    => $data['description'] ?? null,
            ':price'          => $data['price'],
            ':category_id'    => $data['category_id'],
            ':stock_quantity' => $data['stock_quantity'] ?? 0,
            ':sku'            => $data['sku'],
            ':status'         => $data['status'] ?? 1,
        ];

        if (isset($data['image'])) {
            $params[':image'] = $data['image'];
        }

        return $stmt->execute($params);
    }

    /**
     * Delete a product
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM products WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Get product image filename
     */
    public function getImage(int $id): ?string
    {
        $stmt = $this->db->prepare('SELECT image FROM products WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result['image'] ?? null;
    }

    /**
     * Count total products
     */
    public function count(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) as total FROM products');
        return (int) $stmt->fetch()['total'];
    }

    /**
     * Count products by status
     */
    public function countByStatus(int $status): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) as total FROM products WHERE status = :status'
        );
        $stmt->execute([':status' => $status]);
        return (int) $stmt->fetch()['total'];
    }

    /**
     * Get latest products
     */
    public function getLatest(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, c.name as category_name
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             ORDER BY p.created_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
