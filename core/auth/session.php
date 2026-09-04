<?php
declare(strict_types=1);

/**
 * SmartToolz central authentication bridge.
 * Uses the existing Creator AI authentication/session as the single source of truth.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function st_is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function st_user(): ?array
{
    if (!st_is_logged_in()) return null;
    try {
        return getCreatorUser((int)$_SESSION['user_id']);
    } catch (Throwable) {
        return null;
    }
}

function st_login_url(): string
{
    return '/creator-ai/auth/google-login.php';
}

function st_logout_url(): string
{
    return '/auth/logout.php';
}

function st_require_login(): void
{
    if (!st_is_logged_in()) {
        $return = (string)($_SERVER['REQUEST_URI'] ?? '/');
        header('Location: ' . st_login_url() . '?return=' . rawurlencode($return), true, 302);
        exit;
    }
}
