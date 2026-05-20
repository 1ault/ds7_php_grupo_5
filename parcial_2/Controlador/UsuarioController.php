<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Modelo\Usuario;

class UsuarioController
{

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

        $result = self::logicRegistro($_POST);

        if (!$result['success']) 
        {
            $_SESSION['user_logs'] = $result['user_logs'];
            header('Location: /registro');
            exit;
        }

        $_SESSION['user_logs'] = $result['user_logs'];
        header('Location: /login');
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

        if (strlen($password) <= 15) $logs[] = 'La contraseña debe tener minimo 15 caracteres.';
        if (strlen($password) >= 200) $logs[] = 'La contraseña debe tener maximo 200 caracteres.';
        if (!preg_match('/[a-z]/', $password)) $logs[] = 'La contraseña debe tener mínimo una minúscula.';
        if (!preg_match('/[A-Z]/', $password)) $logs[] = 'La contraseña debe tener mínimo una mayúscula.';
        if (!preg_match('/\d/', $password))    $logs[] = 'La contraseña debe tener mínimo un número.';
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) $logs[] = 'Debe contener mínimo un carácter especial.';
        if (!empty($logs)) return ['success' => false, 'user_logs' => $logs];

        $modelo_usuario = new Usuario();
        if ($modelo_usuario->existeUsuario($usuario)) {
            $logs[] = 'El usuario ya existe.';
        }
        if (!empty($logs)) return ['success' => false, 'user_logs' => $logs];

        $resultado = $modelo_usuario->registrar($usuario, $password);
        if (!$resultado) $logs[] = 'Error al registrar usuario.';
        if (!empty($logs)) return ['success' => false, 'user_logs' => $logs];

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

        $result = self::logicLogin($_POST);
        
        if (!$result['success']) 
        {
            $_SESSION['user_logs'] = $result['user_logs'];
            header('Location: /login');
            exit;
        }

        $_SESSION['user_logs'] = $result['user_logs'];

        // Redirigir según rol
        if (($_SESSION['usuario_rol'] ?? '') === 'rh') {
            header('Location: /admin');
        } else {
            header('Location: /aspirante');
        }
    }

    public static function postLogout(): void
    {
        session_unset();
        session_destroy();
        header('Location: /login');
    }

    public static function logicLogin(array $data): array
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

        if (strlen($password) <= 15) $logs[] = 'La contraseña debe tener minimo 15 caracteres.';
        if (strlen($password) >= 128) $logs[] = 'La contraseña debe tener maximo 200 caracteres.';
        if (!preg_match('/[a-z]/', $password)) $logs[] = 'La contraseña debe tener mínimo una minúscula.';
        if (!preg_match('/[A-Z]/', $password)) $logs[] = 'La contraseña debe tener mínimo una mayúscula.';
        if (!preg_match('/\d/', $password))    $logs[] = 'La contraseña debe tener mínimo un número.';
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) $logs[] = 'Debe contener mínimo un carácter especial.';
        if (!empty($logs)) return ['success' => false, 'user_logs' => $logs];

        $modelo_usuario = new Usuario();
        $usuarioDB      = $modelo_usuario->obtenerUsuario($usuario, $password);

        if (empty($usuarioDB)) {
            $logs[] = 'Usuario o contraseña incorrectos.';
        }
        if (!empty($logs)) return ['success' => false, 'user_logs' => $logs];

        session_regenerate_id(true);

        $_SESSION['session_expired']    = time() + SESSION_TIMEOUT;
        $_SESSION["usuario_id"]         = $usuarioDB["id"];
        $_SESSION["usuario_nombre"]     = $usuarioDB["usuario"];
        $_SESSION["usuario_rol"]        = $usuarioDB["rol"];
        $_SESSION["usuario_create_at"]  = $usuarioDB["created_at"];

        return ['success' => true, 'user_logs' => ["Usuario login correctamente."]];
    }
}
