<?php
/**
 * File: helpers/Session.php
 * Purpose: Flash message and session helper
 */

class Session
{
    /**
     * Set a flash message
     */
    public static function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type'    => $type,
            'message' => $message,
        ];
    }

    /**
     * Check if a flash message exists
     */
    public static function hasFlash(): bool
    {
        return isset($_SESSION['flash']);
    }

    /**
     * Get and clear flash message
     */
    public static function getFlash(): ?array
    {
        if (!isset($_SESSION['flash'])) {
            return null;
        }

        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }

    /**
     * Render flash message HTML (Bootstrap 5 alert)
     */
    public static function renderFlash(): void
    {
        if (!self::hasFlash()) {
            return;
        }

        $flash = self::getFlash();
        $type  = htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8');
        $msg   = htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8');

        printf(
            '<div class="alert alert-%s alert-dismissible fade show" role="alert">
                %s
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>',
            $type === 'error' ? 'danger' : $type,
            $msg
        );
    }

    /**
     * Set session value
     */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get session value
     */
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if session key exists
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove session key
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroy entire session
     */
    public static function destroy(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }
}
