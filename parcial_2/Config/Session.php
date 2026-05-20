<?php
declare(strict_types=1);

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

define('MINUTE', 60);
define('SESSION_TIMEOUT', 30 * MINUTE);
define('SESSION_TIMEOUT_NOW', 0);

session_start();

// ── Security headers ────────────────────────────────────────────────────────
// Evita que la app se incruste en iframes de otros dominios (clickjacking)
header('X-Frame-Options: DENY');
// Evita que el navegador intente adivinar el Content-Type (MIME sniffing)
header('X-Content-Type-Options: nosniff');
// Limita las fuentes de recursos permitidas (XSS de segundo nivel)
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");
// No enviar el Referer a otros dominios
header('Referrer-Policy: same-origin');
// Deshabilitar cache en respuestas autenticadas
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

// ── Expiración de sesión ────────────────────────────────────────────────────
if (isset($_SESSION['session_expired']) && $_SESSION['session_expired'] < time()) {
    session_unset();
    session_destroy();
    exit;
}

$_SESSION['session_expired'] = time() + SESSION_TIMEOUT;
$_SESSION['session_start']   = time();

// ── CSRF token ──────────────────────────────────────────────────────────────
// Regenerar si no existe o si ya expiró (1 hora)
if (
    empty($_SESSION['csrf_token']) ||
    empty($_SESSION['csrf_token_expiry']) ||
    $_SESSION['csrf_token_expiry'] < time()
) {
    $_SESSION['csrf_token']        = bin2hex(random_bytes(32));
    $_SESSION['csrf_token_expiry'] = time() + 3600;
}

// ── Fingerprint de sesión ───────────────────────────────────────────────────
$_SESSION['fingerprint_lenguage'] =
    CryptoVault::hashMessageAuthentication(
        data: $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'unknown'
    );

$_SESSION['fingerprint_browser_identification'] =
    CryptoVault::hashMessageAuthentication(
        data: $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    );

$_SESSION['fingerprint_ip'] =
    CryptoVault::hashMessageAuthentication(
        data: $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    );
