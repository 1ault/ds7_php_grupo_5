<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Modelo\Usuario;

class UsuarioController
{

    public static function vistaRegistrar(): void
    {
        require_once __DIR__ . "/../Vista/Registro.php";
    }

    public static function postRegistrar(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") 
        {
            header('Location: /registro');
            exit;
        }

        $result = self::logicRegistrar($_POST);

        if (!$result['success']) 
        {
            $_SESSION['user_logs'] = $result['user_logs'];
            header('Location: /registro');
            exit;
        }

        header('Location: /login');
    }

    public static function apiRegistrar(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER["REQUEST_METHOD"] !== "POST") 
        {
            http_response_code(405);
            echo json_encode(["error" => "Only POST"]);
            return;
        }

        $result = self::logicRegistrar($_POST);

        if (!$result['success']) 
        {
            http_response_code(400);
            echo json_encode($result);
            return;
        }

        exit;    
    }

    public static function logicRegistrar($data): array
    {

        $usuario = trim($data['usuario'] ?? '');
        $password = trim($data['password'] ?? '');
        $logs = [];

        // Check input (Validar usuario && password)
        if($usuario === '' && $password === '')
        {
            $logs[] = 'Todos los campos son obligatorios.';
        }

        if (!empty($logs)) {
            return ['success' => false, 'user_logs' => $logs];
        }


        // Validar usuario
        if ($usuario === '')
        {
            $logs[] = 'El usuario no puede estar vacio.';
        }

        // a to z; A to Z; 0 to 9; min 2, max 20;
        if (!preg_match("/^[a-zA-Z0-9_]{2,20}$/", $usuario))
        { 
            $logs[] = 
            'El usuario debe tener entre 2 y 20 caracteres y solo puede contener letras, números y guion bajo.';
        }

        
        // Validar password
        if ($password === '')
        {
            $logs[] = 'La contraseña no puede estar vacía.';
        }

        if (!empty($logs)) {
            return ['success' => false, 'user_logs' => $logs];
        }

        // Length min
        if (strlen($password) < 15) 
        {
            $logs[] = 'La contraseña debe tener minimo 15 caracteres.';
        }

        // Length max
        if (strlen($password) > 200) 
        {
            $logs[] = 'La contraseña debe tener maximo 200 caracteres.';
        }

        // Lowercase
        if (!preg_match('/[a-z]/', $password)) {
            $logs[] = 'La contraseña debe tener mínimo una minúscula.';
        }

        // Uppercase
        if (!preg_match('/[A-Z]/', $password)) {
            $logs[] = 'La contraseña debe tener mínimo una mayúscula.';
        }

        // Number
        if (!preg_match('/\d/', $password)) {
            $logs[] = 'La contraseña debe tener mínimo un número.';
        }

        // Special char (match all not characters && numbers)  ^ = !
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            $logs[] = 'Debe contener mínimo un carácter especial.';
        }

        if (!empty($logs)) {
            return ['success' => false, 'user_logs' => $logs];
        }
        
        // Validar usuario repetido
        $modelo_usuario = new Usuario();
        if ($modelo_usuario->existeUsuario($usuario))
        {
            $logs[] = 'El usuario ya existe.';
        }
        
        if (!empty($logs)) {
            return ['success' => false, 'user_logs' => $logs];
        }

        // Registrar usuario
        $resultado = $modelo_usuario->registrar(
            $usuario,
            $password
        );
        
        if (!$resultado)
        {
            $logs[] = 'Error al registrar usuario.';
        }


        if (!empty($logs)) {
            return ['success' => false, 'user_logs' => $logs];
        }

        return ['success' => true, 'user_logs' => "Usuario registrado correctamente."];
    }
}
