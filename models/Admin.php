<?php
/**
 * File: models/Admin.php
 * Purpose: Admin model - handles authentication queries
 */

class Admin
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find admin by username
     */
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, username, email, password FROM admins WHERE username = :username LIMIT 1'
        );
        $stmt->execute([':username' => $username]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Find admin by ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, username, email FROM admins WHERE id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Verify password against hash
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(int $id): void
    {
        $stmt = $this->db->prepare(
            'UPDATE admins SET updated_at = NOW() WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);
    }
}
