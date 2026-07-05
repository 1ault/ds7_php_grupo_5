<?php
declare(strict_types=1);

// Idempotente: no iniciar sesión dos veces
if (session_status() === PHP_SESSION_ACTIVE) return;

use Root\Program\Utils\CryptoVault;

ini_set('session.use_only_cookies', '1');
ini_set('session.use_strict_mode', '1');

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict',
]);

if (!defined('MINUTE'))          define('MINUTE', 60);
if (!defined('SESSION_TIMEOUT')) define('SESSION_TIMEOUT', 30 * MINUTE);

session_start();

// ── Security headers ─────────────────────────────────────────────────────────
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https://image.tmdb.org https:;");
header('Referrer-Policy: same-origin');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

// ── Expiración de sesión ─────────────────────────────────────────────────────
if (!empty($_SESSION['usuario_id']) && isset($_SESSION['session_expired'])) {
    if ($_SESSION['session_expired'] < time()) {
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id(true);
        header('Location: /login');
        exit;
    }
}

$_SESSION['session_expired'] = time() + SESSION_TIMEOUT;

// ── CSRF token ────────────────────────────────────────────────────────────────
if (
    empty($_SESSION['csrf_token']) ||
    empty($_SESSION['csrf_token_expiry']) ||
    $_SESSION['csrf_token_expiry'] < time()
) {
    $_SESSION['csrf_token']        = bin2hex(random_bytes(32));
    $_SESSION['csrf_token_expiry'] = time() + 3600;
}

// ── Fingerprint ───────────────────────────────────────────────────────────────
$_SESSION['fingerprint_ip'] =
    CryptoVault::hashMessageAuthentication(
        data: $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    );
