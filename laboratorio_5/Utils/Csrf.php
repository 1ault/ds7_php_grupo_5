<?php
declare(strict_types=1);

namespace Root\Program\Utils;

class Csrf
{
    public static function tokenCSRFGet(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $one_hour = time() + 3600;
            $_SESSION['csrf_token_expiry'] = $one_hour;
        }
        return $_SESSION['csrf_token'];
    }

    public static function tokenCSRFValidate(mixed $token): bool {
        // === Check server
        if ($_SERVER['REQUEST_METHOD'] != 'POST')
        {
            return false;
        }

        // === Check if the user altered the token
        if (!isset($token)) {
            return false;
        }

        if (!is_string($token)) {
            return false;
        }

        if (strlen($token) !== 64) {
            return false;
        }

        if (!ctype_xdigit($token)) {
            return false;
        }

        // === Check csrf token ===        
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }

        if (!isset($_SESSION['csrf_token_expiry'])) {
            return false;
        }

        // === Check expiry
        $csrf_token_expiry = 
            filter_var($_SESSION['csrf_token_expiry'], FILTER_VALIDATE_INT);
        if ($csrf_token_expiry === false) {
            unset(
                $_SESSION['csrf_token'], 
                $_SESSION['csrf_token_expiry']
            );
            return false;
        }

        if (time() > $_SESSION['csrf_token_expiry']) {
            unset(
                $_SESSION['csrf_token'], 
                $_SESSION['csrf_token_expiry']
            );
            return false;
        }

        // === Check csrf token 
        if (strlen($_SESSION['csrf_token']) !== 64) {
            unset(
                $_SESSION['csrf_token'], 
                $_SESSION['csrf_token_expiry']
            );
            return false;
        }


        if (!ctype_xdigit($_SESSION['csrf_token'])) {
            unset(
                $_SESSION['csrf_token'], 
                $_SESSION['csrf_token_expiry']
            );
            return false;
        }

        $check = hash_equals($_SESSION['csrf_token'], $token);

        unset(
            $_SESSION['csrf_token'], 
            $_SESSION['csrf_token_expiry']
        );

        return $check;
    }
}
