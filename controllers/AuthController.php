<?php
/**
 * File: controllers/AuthController.php
 * Purpose: Handles admin login/logout logic
 */

class AuthController
{
    private Admin $adminModel;

    public function __construct()
    {
        $this->adminModel = new Admin();
    }

    /**
     * Process login attempt
     */
    public function login(string $username, string $password, string $csrfToken): array
    {
        // Verify CSRF token
        if (!verifyCsrfToken($csrfToken)) {
            return ['success' => false, 'message' => 'Invalid security token. Please try again.'];
        }

        // Validate input
        $validator = new Validator();
        $validator
            ->required('username', 'Username', $username)
            ->required('password', 'Password', $password);

        if (!$validator->passes()) {
            return ['success' => false, 'message' => $validator->firstError()];
        }

        // Find admin
        $admin = $this->adminModel->findByUsername($username);
        if (!$admin) {
            return ['success' => false, 'message' => 'Invalid username or password.'];
        }

        // Verify password
        if (!$this->adminModel->verifyPassword($password, $admin['password'])) {
            return ['success' => false, 'message' => 'Invalid username or password.'];
        }

        // Regenerate session ID for security
        session_regenerate_id(true);

        // Set session data
        Session::set('admin_id', $admin['id']);
        Session::set('admin_username', $admin['username']);
        Session::set('admin_logged_in', true);

        // Update last login
        $this->adminModel->updateLastLogin($admin['id']);

        return ['success' => true, 'message' => 'Login successful.'];
    }

    /**
     * Check if admin is logged in
     */
    public static function isLoggedIn(): bool
    {
        return Session::get('admin_logged_in') === true
            && Session::get('admin_id') !== null;
    }

    /**
     * Get current admin info
     */
    public static function currentUser(): ?array
    {
        if (!self::isLoggedIn()) {
            return null;
        }

        return [
            'id'       => Session::get('admin_id'),
            'username' => Session::get('admin_username'),
        ];
    }

    /**
     * Logout - destroy session
     */
    public static function logout(): void
    {
        Session::destroy();
    }
}
