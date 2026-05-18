<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Modelo\Usuario;

class LoginController
{
    public static function vistaLogin(): void
    {
        //  session_start();
        require_once __DIR__ ."/../Vista/login.php";
    }

    public static function postLogin(): void
    {       
        if ($_SERVER["REQUEST_METHOD"] !== "POST") 
        {
            header('Location: /login');
            exit;
        }

        $result = self::logicLogin($_POST);
        
        if (!$result['success']) 

        {
            $_SESSION['user_logs'] = $result['user_logs'];
            header('Location: /registro');
            exit;
        }
    }

    public static function logicLogin: array
    {

        $usuario = trim($_POST["usuario"]);
        $password = trim($_POST["password"]);
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
        $modelo_usuario = new Usuario();
        $usuarioDB = $modelo_usuario>obtenerUsuario($usuario);

        if(!$usuarioDB)
        {
            $logs[] =  
                'Usuario o contraseña incorrectos.';
        }

        if (!empty($logs)) {
            return ['success' => false, 'user_logs' => $logs];
        }

        $result = password_verify(
                    $password,
                    $usuarioDB["password"]
                );

        if(!$result)
        { 
            $logs[] = 
                "Usuario o contraseña incorrectos.";
        }


        $_SESSION["usuario"] = $usuarioDB["usuario"];
        $_SESSION["usuario_id"] = $usuarioDB["id"];

        header("Location: formularioP.php");
        exit;
    }
}
