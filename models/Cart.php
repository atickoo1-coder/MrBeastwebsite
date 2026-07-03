<?php
class Cart
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getItems(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT c.*, p.name, p.price, p.image, p.image_hover, p.stock_quantity,
                    p.sku, p.colors, p.sizes, p.type
             FROM cart c
             JOIN products p ON c.product_id = p.id
             WHERE c.user_id = :user_id
             ORDER BY c.created_at DESC'
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function addItem(int $userId, int $productId, int $quantity, ?string $size, ?string $color): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO cart (user_id, product_id, quantity, size, color)
             VALUES (:user_id, :product_id, :quantity, :size, :color)
             ON DUPLICATE KEY UPDATE quantity = quantity + :qty2'
        );
        return $stmt->execute([
            ':user_id'     => $userId,
            ':product_id'  => $productId,
            ':quantity'    => $quantity,
            ':size'        => $size,
            ':color'       => $color,
            ':qty2'        => $quantity,
        ]);
    }

    public function updateQuantity(int $cartId, int $userId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->removeItem($cartId, $userId);
        }
        $stmt = $this->db->prepare(
            'UPDATE cart SET quantity = :quantity WHERE id = :id AND user_id = :user_id'
        );
        return $stmt->execute([
            ':quantity' => $quantity,
            ':id'       => $cartId,
            ':user_id'  => $userId,
        ]);
    }

    public function removeItem(int $cartId, int $userId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM cart WHERE id = :id AND user_id = :user_id');
        return $stmt->execute([':id' => $cartId, ':user_id' => $userId]);
    }

    public function clearCart(int $userId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM cart WHERE user_id = :user_id');
        return $stmt->execute([':user_id' => $userId]);
    }

    public function getCount(int $userId): int
    {
        $stmt = $this->db->prepare(
            'SELECT COALESCE(SUM(quantity), 0) as count FROM cart WHERE user_id = :user_id'
        );
        $stmt->execute([':user_id' => $userId]);
        return (int) $stmt->fetch()['count'];
    }

    public function getTotal(int $userId): float
    {
        $stmt = $this->db->prepare(
            'SELECT COALESCE(SUM(c.quantity * p.price), 0) as total
             FROM cart c
             JOIN products p ON c.product_id = p.id
             WHERE c.user_id = :user_id'
        );
        $stmt->execute([':user_id' => $userId]);
        return (float) $stmt->fetch()['total'];
    }
}
