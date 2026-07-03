<?php
class Order
{
    private PDO $db;
    private const TAX_RATE = 0.08;
    private const FREE_SHIPPING_THRESHOLD = 75;
    private const SHIPPING_COST = 9.99;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create(int $userId, array $shipping, array $cartItems): array
    {
        $this->db->beginTransaction();
        try {
            $orderNumber = $this->generateOrderNumber();

            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += (float) $item['price'] * (int) $item['quantity'];
            }

            $shippingCost = $subtotal >= self::FREE_SHIPPING_THRESHOLD ? 0 : self::SHIPPING_COST;
            $tax = round($subtotal * self::TAX_RATE, 2);
            $total = round($subtotal + $shippingCost + $tax, 2);

            $stmt = $this->db->prepare(
                'INSERT INTO orders
                 (order_number, user_id, subtotal, shipping_cost, tax, total,
                  shipping_first_name, shipping_last_name, shipping_email, shipping_phone,
                  shipping_address1, shipping_address2, shipping_city,
                  shipping_state, shipping_zip, shipping_country, status)
                 VALUES
                 (:order_number, :user_id, :subtotal, :shipping_cost, :tax, :total,
                  :shipping_first_name, :shipping_last_name, :shipping_email, :shipping_phone,
                  :shipping_address1, :shipping_address2, :shipping_city,
                  :shipping_state, :shipping_zip, :shipping_country, :status)'
            );
            $stmt->execute([
                ':order_number'         => $orderNumber,
                ':user_id'              => $userId,
                ':subtotal'             => $subtotal,
                ':shipping_cost'        => $shippingCost,
                ':tax'                  => $tax,
                ':total'                => $total,
                ':shipping_first_name'  => $shipping['first_name'],
                ':shipping_last_name'   => $shipping['last_name'],
                ':shipping_email'       => $shipping['email'],
                ':shipping_phone'       => $shipping['phone'] ?? null,
                ':shipping_address1'    => $shipping['address1'],
                ':shipping_address2'    => $shipping['address2'] ?? null,
                ':shipping_city'        => $shipping['city'],
                ':shipping_state'       => $shipping['state'],
                ':shipping_zip'         => $shipping['zip'],
                ':shipping_country'     => $shipping['country'] ?? 'United States',
                ':status'               => 'pending',
            ]);
            $orderId = (int) $this->db->lastInsertId();

            $insertItemStmt = $this->db->prepare(
                'INSERT INTO order_items
                 (order_id, product_id, product_name, product_price, quantity, size, color, subtotal)
                 VALUES
                 (:order_id, :product_id, :product_name, :product_price, :quantity, :size, :color, :subtotal)'
            );

            $updateStockStmt = $this->db->prepare(
                'UPDATE products SET stock_quantity = stock_quantity - :qty WHERE id = :id AND stock_quantity >= :qty2'
            );

            foreach ($cartItems as $item) {
                $itemSubtotal = (float) $item['price'] * (int) $item['quantity'];

                $insertItemStmt->execute([
                    ':order_id'       => $orderId,
                    ':product_id'     => $item['product_id'],
                    ':product_name'   => $item['name'],
                    ':product_price'  => $item['price'],
                    ':quantity'       => $item['quantity'],
                    ':size'           => $item['size'] ?? null,
                    ':color'          => $item['color'] ?? null,
                    ':subtotal'       => $itemSubtotal,
                ]);

                $updateStockStmt->execute([
                    ':qty'  => $item['quantity'],
                    ':id'   => $item['product_id'],
                    ':qty2' => $item['quantity'],
                ]);
            }

            $this->db->commit();
            return ['success' => true, 'order_id' => $orderId, 'order_number' => $orderNumber, 'total' => $total];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Order failed: ' . $e->getMessage()];
        }
    }

    public function getById(int $orderId, int $userId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM orders WHERE id = :id AND user_id = :user_id LIMIT 1'
        );
        $stmt->execute([':id' => $orderId, ':user_id' => $userId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getByOrderNumber(string $orderNumber, int $userId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM orders WHERE order_number = :order_number AND user_id = :user_id LIMIT 1'
        );
        $stmt->execute([':order_number' => $orderNumber, ':user_id' => $userId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getItems(int $orderId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM order_items WHERE order_id = :order_id ORDER BY id ASC'
        );
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll();
    }

    public function getOrdersByUser(int $userId, int $limit = 10, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countByUser(int $userId): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) as total FROM orders WHERE user_id = :user_id'
        );
        $stmt->execute([':user_id' => $userId]);
        return (int) $stmt->fetch()['total'];
    }

    private function generateOrderNumber(): string
    {
        $prefix = 'MB';
        $timestamp = strtoupper(base_convert(time(), 10, 36));
        $random = strtoupper(bin2hex(random_bytes(3)));
        return $prefix . $timestamp . $random;
    }
}
