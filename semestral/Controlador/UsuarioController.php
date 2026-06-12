<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Modelo\Usuario;
use Root\Program\Utils\BruteForce;

class UsuarioController
{
    private static function validarCsrf(array $data): bool
    {
        $token = $data['csrf_token'] ?? '';
        return !empty($_SESSION['csrf_token'])
            && !empty($token)
            && hash_equals($_SESSION['csrf_token'], $token)
            && (!isset($_SESSION['csrf_token_expiry']) || $_SESSION['csrf_token_expiry'] >= time());
    }

    // ── Vistas ───────────────────────────────────────────────────────────────

    public static function vistaRegistro(): void
    {
        require_once __DIR__ . '/../Vista/Registro.php';
    }

    public static function vistaLogin(): void
    {
        require_once __DIR__ . '/../Vista/Login.php';
    }

    // ── POST: Registro ────────────────────────────────────────────────────────

    public static function postRegistro(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /registro'); exit;
        }
        if (!self::validarCsrf($_POST)) {
            $_SESSION['user_logs'] = ['Solicitud inválida. Recarga la página.'];
            header('Location: /registro'); exit;
        }
        $result = self::logicRegistro($_POST);
        $_SESSION['user_logs'] = $result['user_logs'];
        header('Location: ' . ($result['success'] ? '/login' : '/registro'));
    }

    public static function logicRegistro(array $data): array
    {
        $usuario  = trim($data['usuario']  ?? '');
        $password = trim($data['password'] ?? '');
        $logs     = [];

        if ($usuario === '' || $password === '') {
            return ['success' => false, 'user_logs' => ['Todos los campos son obligatorios.']];
        }
        if (!preg_match('/^[a-zA-Z0-9_]{2,20}$/', $usuario)) {
            $logs[] = 'El usuario debe tener entre 2 y 20 caracteres (letras, números y _).';
        }
        if (strlen($password) < 15)              $logs[] = 'La contraseña debe tener mínimo 15 caracteres.';
        if (strlen($password) > 200)             $logs[] = 'La contraseña debe tener máximo 200 caracteres.';
        if (!preg_match('/[a-z]/', $password))   $logs[] = 'Debe tener al menos una minúscula.';
        if (!preg_match('/[A-Z]/', $password))   $logs[] = 'Debe tener al menos una mayúscula.';
        if (!preg_match('/\d/', $password))      $logs[] = 'Debe tener al menos un número.';
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) $logs[] = 'Debe tener al menos un carácter especial.';
        if (!empty($logs)) return ['success' => false, 'user_logs' => $logs];

        $modelo = new Usuario();
        if ($modelo->existeUsuario($usuario)) {
            return ['success' => false, 'user_logs' => ['No fue posible completar el registro. Verifica los datos.']];
        }

        $modelo->registrar($usuario, $password);
        return ['success' => true, 'user_logs' => ['Cuenta creada correctamente. ¡Inicia sesión!']];
    }

    // ── POST: Login ───────────────────────────────────────────────────────────

    public static function postLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login'); exit;
        }
        if (!self::validarCsrf($_POST)) {
            $_SESSION['user_logs'] = ['Solicitud inválida. Recarga la página.'];
            header('Location: /login'); exit;
        }
        $result = self::logicLogin($_POST);
        $_SESSION['user_logs'] = $result['user_logs'];
        if (!$result['success']) {
            header('Location: /login'); exit;
        }
        setcookie(
            'cm_usuario',
            htmlspecialchars($_SESSION['usuario_nombre'] ?? '', ENT_QUOTES, 'UTF-8'),
            time() + 30 * 86400, '/', '', isset($_SERVER['HTTPS']), false
        );
        header('Location: /home');
    }

    public static function logicLogin(array $data): array
    {
        $usuario  = trim($data['usuario']  ?? '');
        $password = trim($data['password'] ?? '');
        $logs     = [];

        $bruteForce = new BruteForce();
        try {
            if ($usuario !== '' && $bruteForce->isBlocked($usuario)) {
                return ['success' => false, 'user_logs' => ['Cuenta bloqueada por demasiados intentos. Espera 2 minutos.']];
            }
        } catch (\Throwable) {}

        if ($usuario === '' || $password === '') {
            return ['success' => false, 'user_logs' => ['Todos los campos son obligatorios.']];
        }
        if (!preg_match('/^[a-zA-Z0-9_]{2,20}$/', $usuario)) $logs[] = 'Usuario inválido.';
        if (strlen($password) < 15 || strlen($password) > 200) $logs[] = 'Contraseña inválida.';
        if (!empty($logs)) return ['success' => false, 'user_logs' => $logs];

        $modelo    = new Usuario();
        $usuarioDB = $modelo->obtenerUsuario($usuario, $password);

        if (empty($usuarioDB)) {
            try { $bruteForce->loginAttempt(false, $usuario); } catch (\Throwable) {}
            return ['success' => false, 'user_logs' => ['Usuario o contraseña incorrectos.']];
        }

        session_regenerate_id(true);
        $_SESSION['session_expired']   = time() + SESSION_TIMEOUT;
        $_SESSION['usuario_id']        = $usuarioDB['id'];
        $_SESSION['usuario_nombre']    = $usuarioDB['usuario'];
        $_SESSION['usuario_rol']       = $usuarioDB['rol'];
        $_SESSION['usuario_create_at'] = $usuarioDB['created_at'];

        try {
            $bruteForce->loginAttempt(true, $usuario);
            $bruteForce->clearAttempts($usuario);
        } catch (\Throwable) {}

        return ['success' => true, 'user_logs' => ['Sesión iniciada correctamente.']];
    }

    // ── POST: Logout ──────────────────────────────────────────────────────────

    public static function postLogout(): void
    {
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id(true);
        setcookie('cm_usuario', '', time() - 3600, '/');
        setcookie('cm_tema',    '', time() - 3600, '/');
        header('Location: /login');
    }
}
