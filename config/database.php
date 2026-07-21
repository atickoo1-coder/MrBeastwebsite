<?php
/**
 * File: config/database.php
 * Purpose: PDO database connection class (Singleton)
 * Returns: PDO instance with MySQL / SQLite auto-fallback
 */

class Database
{
    private static ?PDO $instance = null;

    private const DB_HOST = '127.0.0.1';
    private const DB_NAME = 'mrbeast_store';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private const DB_CHARSET = 'utf8mb4';

    /**
     * Get singleton PDO connection
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            // 1. Try MySQL connection first
            try {
                $dsn = sprintf(
                    'mysql:host=%s;dbname=%s;charset=%s',
                    self::DB_HOST,
                    self::DB_NAME,
                    self::DB_CHARSET
                );
                self::$instance = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
            } catch (PDOException $e) {
                // 2. Fall back to embedded SQLite database
                $dbDir = __DIR__ . '/../database';
                if (!is_dir($dbDir)) {
                    @mkdir($dbDir, 0777, true);
                }
                $sqlitePath = $dbDir . '/mrbeast_store.sqlite';
                self::$instance = new PDO('sqlite:' . $sqlitePath, null, null, $options);
                self::initSQLiteTables(self::$instance);
            }
        }

        return self::$instance;
    }

    private static function initSQLiteTables(PDO $db): void
    {
        $db->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                first_name TEXT NOT NULL,
                last_name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                phone TEXT DEFAULT NULL,
                address_line1 TEXT DEFAULT NULL,
                address_line2 TEXT DEFAULT NULL,
                city TEXT DEFAULT NULL,
                state TEXT DEFAULT NULL,
                zip_code TEXT DEFAULT NULL,
                country TEXT DEFAULT 'United States',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                description TEXT DEFAULT NULL,
                status INTEGER DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                description TEXT DEFAULT NULL,
                price REAL NOT NULL,
                category_id INTEGER,
                stock_quantity INTEGER DEFAULT 100,
                sku TEXT UNIQUE,
                status INTEGER DEFAULT 1,
                image TEXT DEFAULT NULL,
                image_hover TEXT DEFAULT NULL,
                colors TEXT DEFAULT NULL,
                sizes TEXT DEFAULT NULL,
                badges TEXT DEFAULT NULL,
                type TEXT DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS cart (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                product_id INTEGER NOT NULL,
                quantity INTEGER DEFAULT 1,
                size TEXT DEFAULT NULL,
                color TEXT DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS orders (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                order_number TEXT NOT NULL UNIQUE,
                user_id INTEGER NOT NULL,
                total_amount REAL NOT NULL,
                status TEXT DEFAULT 'pending',
                payment_status TEXT DEFAULT 'unpaid',
                shipping_address TEXT DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");
    }

    /**
     * Prevent cloning and unserialization
     */
    private function __clone() {}
    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize singleton');
    }
}

