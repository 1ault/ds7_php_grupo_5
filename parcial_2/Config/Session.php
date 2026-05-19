<?php
/*
    // https://www.php.net/manual/en/session.security.ini.php
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1'); // Prevent JavaScript to acces to session cookie
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? '1' : '0');
    ini_set('session.cookie_samesite', 'Strict');
    // session.gc_maxlifetime is a setting for deleting obsolete session ID. Reliance on this setting is not recommended. Developers should manage the lifetime of sessions with a timestamp by themselves.
    define('MINUTE', 60);
    define('SESSION_TIMEOUT', 30 * MINUTE);
 */

ini_set('session.use_only_cookies', '1');

// This prevents the session module to use an uninitialized session ID. 
// Can prevent an attacker-initialized session ID of being used.
// This prevents the session module to use an uninitialized session ID.
ini_set('session.use_strict_mode', '1'); 

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict' // Is a way to mitigate CSRF (Cross Site Request Forgery) attacks. 
]);

// session.gc_maxlifetime is a setting for deleting obsolete session ID. Reliance on this setting is not recommended. Developers should manage the lifetime of sessions with a timestamp by themselves.
define('MINUTE', 60);
define('SESSION_TIMEOUT', 30 * MINUTE);
define('SESSION_TIMEOUT_NOW', 0);


// https://www.w3resource.com/php-exercises/cookies-sessions/php-cookies-sessions-exercise-11.php

session_start();

if 
(
    isset($_SESSION['session_expired']) &&
    $_SESSION['session_expired'] < time()
) 
{
    session_unset();
    session_destroy();
    exit;
}

// https://www.w3schools.com/PhP/php_superglobals_server.asp
// https://www.php.net/manual/en/reserved.variables.server.php
$_SESSION['session_expired'] = time() + SESSION_TIMEOUT;
$_SESSION['session_start'] = time();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $one_hour = time() + 3600;
    $_SESSION['csrf_token_expiry'] = $one_hour;
}
