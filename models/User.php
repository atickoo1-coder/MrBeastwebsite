<?php
class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (first_name, last_name, email, password, phone)
             VALUES (:first_name, :last_name, :email, :password, :phone)'
        );
        $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name'  => $data['last_name'],
            ':email'      => $data['email'],
            ':password'   => $data['password'],
            ':phone'      => $data['phone'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function updateProfile(int $id, array $data): bool
    {
        $sql = 'UPDATE users SET
                first_name = :first_name, last_name = :last_name,
                phone = :phone, address_line1 = :address_line1,
                address_line2 = :address_line2, city = :city,
                state = :state, zip_code = :zip_code, country = :country
                WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':first_name'    => $data['first_name'],
            ':last_name'     => $data['last_name'],
            ':phone'         => $data['phone'] ?? null,
            ':address_line1' => $data['address_line1'] ?? null,
            ':address_line2' => $data['address_line2'] ?? null,
            ':city'          => $data['city'] ?? null,
            ':state'         => $data['state'] ?? null,
            ':zip_code'      => $data['zip_code'] ?? null,
            ':country'       => $data['country'] ?? 'United States',
            ':id'            => $id,
        ]);
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = 'SELECT id FROM users WHERE email = :email';
        $params = [':email' => $email];
        if ($excludeId !== null) {
            $sql .= ' AND id != :exclude_id';
            $params[':exclude_id'] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function updatePassword(int $id, string $newPassword): bool
    {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare('UPDATE users SET password = :password WHERE id = :id');
        return $stmt->execute([':password' => $hash, ':id' => $id]);
    }
}
