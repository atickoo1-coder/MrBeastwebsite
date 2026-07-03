<?php
/**
 * Auth helper for frontend pages
 * Functions for customer authentication checks
 */

function isUserLoggedIn(): bool
{
    return Session::get('user_logged_in') === true
        && Session::get('user_id') !== null;
}

function getCurrentUser(): ?array
{
    if (!isUserLoggedIn()) {
        return null;
    }
    return [
        'id'         => Session::get('user_id'),
        'first_name' => Session::get('user_first_name'),
        'last_name'  => Session::get('user_last_name'),
        'email'      => Session::get('user_email'),
    ];
}

function requireLogin(): void
{
    if (!isUserLoggedIn()) {
        Session::setFlash('warning', 'Please log in to access this page.');
        header('Location: ' . APP_URL . '/auth/login.php');
        exit;
    }
}
