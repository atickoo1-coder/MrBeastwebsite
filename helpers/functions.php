<?php
/**
 * File: helpers/functions.php
 * Purpose: Global utility functions
 */

/**
 * Generate CSRF token and store in session
 */
function generateCsrfToken(): string
{
    $token = bin2hex(random_bytes(32));
    Session::set('csrf_token', $token);
    return $token;
}

/**
 * Verify CSRF token
 */
function verifyCsrfToken(string $token): bool
{
    $stored = Session::get('csrf_token');
    if (!$stored) {
        return false;
    }
    Session::remove('csrf_token');
    return hash_equals($stored, $token);
}

/**
 * Render CSRF hidden input field
 */
function csrfField(): string
{
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Clean/escape output for XSS prevention
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate a URL-friendly slug
 */
function slugify(string $text): string
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

/**
 * Generate unique filename for uploads
 */
function uniqueFilename(string $extension): string
{
    return uniqid('prod_', true) . '.' . $extension;
}

/**
 * Format price with currency symbol
 */
function formatPrice(float $price): string
{
    return '$' . number_format($price, 2);
}

/**
 * Truncate text to a specific length
 */
function truncateText(string $text, int $length = 100): string
{
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

/**
 * Get file extension from mime type
 */
function getExtensionFromMime(string $mimeType): string
{
    $map = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];
    return $map[$mimeType] ?? 'jpg';
}

/**
 * Redirect to a URL
 */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/**
 * Check if current page is active (for sidebar)
 */
function isActive(string $page): string
{
    $current = basename($_SERVER['SCRIPT_NAME']);
    $currentDir = basename(dirname($_SERVER['SCRIPT_NAME']));
    $currentPath = $currentDir . '/' . $current;
    return ($current === $page || $currentPath === $page) ? 'active' : '';
}

/**
 * Generate breadcrumb navigation
 */
function breadcrumbs(array $items): string
{
    $html = '<nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">';
    $html .= '<li class="breadcrumb-item"><a href="' . ADMIN_PATH . '/dashboard.php">Dashboard</a></li>';
    foreach ($items as $label => $link) {
        if ($link) {
            $html .= '<li class="breadcrumb-item"><a href="' . $link . '">' . e($label) . '</a></li>';
        } else {
            $html .= '<li class="breadcrumb-item active">' . e($label) . '</li>';
        }
    }
    $html .= '</ol></nav>';
    return $html;
}

/**
 * Get status badge HTML
 */
function statusBadge(int $status): string
{
    if ($status === 1) {
        return '<span class="badge bg-success">Active</span>';
    }
    return '<span class="badge bg-secondary">Inactive</span>';
}
