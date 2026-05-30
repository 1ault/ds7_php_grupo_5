<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Modelo\Usuario;

class UsuarioController
{

    // ── Helper: validar CSRF ─────────────────────────────────────────────────
    private static function validarCsrf(array $data): bool
    {
        $token = $data['csrf_token'] ?? '';
        return
            !empty($_SESSION['csrf_token']) &&
            !empty($token) &&
            hash_equals($_SESSION['csrf_token'], $token) &&
            (!isset($_SESSION['csrf_token_expiry']) || $_SESSION['csrf_token_expiry'] >= time());
    }

    // ── Vistas ───────────────────────────────────────────────────────────────

    public static function vistaRegistro(): void
    {
        require_once __DIR__ . "/../Vista/Registro.php";
    }

    public static function postRegistro(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST")
        {
            $_SESSION['user_logs'] = ["No metodo Post."];
            header('Location: /registro');
            exit;
        }

        if (!self::validarCsrf($_POST)) {
            $_SESSION['user_logs'] = ["Solicitud inválida. Recarga la página e intenta de nuevo."];
            header('Location: /registro');
            exit;
        }

        $result = self::logicRegistro($_POST);

        if (!$result['success'])
        {
            $_SESSION['user_logs'] = $result['user_logs'];
            header('Location: /registro');
            exit;
        }

        $_SESSION['user_logs'] = $result['user_logs'];
        header('Location: /login');
        exit;
    }

    public static function logicRegistro(array $data): array
    {
        $usuario  = trim($data['usuario']  ?? '');
        $password = trim($data['password'] ?? '');
        $logs     = [];

        if ($usuario === '' && $password === '') {
            $logs[] = 'Todos los campos son obligatorios.';
        }
        if (!empty($logs)) return ['success' => false, 'user_logs' => $logs];

        if ($usuario === '') $logs[] = 'El usuario no puede estar vacio.';
        if (!preg_match("/^[a-zA-Z0-9_]{2,20}$/", $usuario)) {
            $logs[] = 'El usuario debe tener entre 2 y 20 caracteres y solo puede contener letras, números y guion bajo.';
        }

        if ($password === '') $logs[] = 'La contraseña no puede estar vacía.';
        if (!empty($logs)) return ['success' => false, 'user_logs' => $logs];
        if (strlen($password) <= 5) $logs[] = 'La contraseña debe tener minimo 5 caracteres.';
        if (strlen($password) >= 15) $logs[] = 'La contraseña debe tener maximo 15 caracteres.';

        if (!empty($logs)){ return ['success' => false, 'user_logs' => $logs];}

           Usuario::RegistrarUsuario($usuario, $password);
        
            return ['success' => true, 'user_logs' => ["Usuario registrado correctamente."]];
    }

    public static function vistaLogin(): void
    {
        require_once __DIR__ . "/../Vista/Login.php";
    }

    public static function postLogin(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST")
        {
            $_SESSION['user_logs'] = ["No metodo Post."];
            header('Location: /login');
            exit;
        }

        if (!self::validarCsrf($_POST)) {
            $_SESSION['user_logs'] = ["Solicitud inválida. Recarga la página e intenta de nuevo."];
            header('Location: /login');
            exit;
        }

        $result = self::logicLogin($_POST);

        if (!$result['success'])
        {
            $_SESSION['user_logs'] = $result['user_logs'];
            header('Location: /login');
            exit;
        }

        $_SESSION['user_logs'] = $result['user_logs'];
            header('Location: /tareas');
            exit;
        
    }

    public static function postLogout(): void
    {
        // Destruir sesión completamente y regenerar ID para evitar session fixation
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id(true);
        header('Location: /login');
    }

    public static function logicLogin(array $data): array
    {
        $usuario  = trim($data['usuario']  ?? '');
        $password = trim($data['password'] ?? '');
        $logs     = [];

        // Verificar bloqueo por cuenta antes de cualquier validación.

        if ($usuario === '' && $password === '') {
            $logs[] = 'Todos los campos son obligatorios.';
        }
        if (!empty($logs)) return ['success' => false, 'user_logs' => $logs];

        if ($usuario === '') $logs[] = 'El usuario no puede estar vacio.';
        if (!preg_match("/^[a-zA-Z0-9_]{2,20}$/", $usuario)) {
            $logs[] = 'El usuario debe tener entre 2 y 20 caracteres y solo puede contener letras, números y guion bajo.';
        }
        if ($password === '') $logs[] = 'La contraseña no puede estar vacía.';
        if (!empty($logs)) return ['success' => false, 'user_logs' => $logs];
        if (strlen($password) <= 5) $logs[] = 'La contraseña debe tener minimo 5 caracteres.';
        if (strlen($password) >= 15) $logs[] = 'La contraseña debe tener maximo 15 caracteres.'; 
        if (!empty($logs)) return ['success' => false, 'user_logs' => $logs];
       
       if (Usuario::IniciarSesion($usuario, $password)) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['password'] = $password;

    return [
        'success' => true, 'user_logs' => ["Inicio de sesión exitoso. Bienvenido, " . htmlspecialchars($usuario) . "!"]];
}

    return ['success' => false,'user_logs' => ["Credenciales incorrectas."]];
    }
}
