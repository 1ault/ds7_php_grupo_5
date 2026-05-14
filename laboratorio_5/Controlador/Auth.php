<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Modelo\Usuario;
use Root\Program\Utils\Encrypted;

class Auth
{
    public static function viewLogin(): void
    {

        ob_start();
        require_once PATH_ROOT_VISTA . '/Login.php';
        $main = ob_get_clean();

        $title = 'login';
        
        require_once PATH_ROOT_VISTA_LAYOUT . '/Main.php';
    }

    public static function viewRegistro(): void
    {
        ob_start();
        require_once PATH_ROOT_VISTA . '/Registro.php';
        $main = ob_get_clean();

        $title = 'registro';
        
        require_once PATH_ROOT_VISTA_LAYOUT . '/Main.php';
    }


    public static function apiLogin(): void
    {

        $password = trim(filter_input(INPUT_POST, 'password', FILTER_DEFAULT) ?? '');
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');

        $logs = $_GET['logs'] ?? [];

        if (empty($password)) {
            $logs['password'] = 'empty password';
        }

        if (empty($email)) {
            $logs['email_empty'] = 'empty email';
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $logs['email_invalid'] = 'invalid email';
        }

        if (!empty($logs)) {
            $query = http_build_query([
                'logs' => $logs,
            ]);

            header('Location: /login?' . $query);
            exit;
        }

        $usuario = new 
            Usuario(
                name: '',
                password: $password,
                email: $email,
                indexing_email: Encrypted::hashMessageAuthenticationCodeData(data: $email)
            );

        $info = $usuario->login(); 
        if(!empty($info))
        {
            
            $logs['user_not_valid'] = $info;

            $query = http_build_query([
                'logs' => $logs,
            ]);

            header('Location: /login?' . $query);
            exit; 
        }


        header('Location: /home');
        exit;
    }

    public static function apiRegistro(): void
    {

        $name = trim(filter_input(INPUT_POST, 'nombre', FILTER_DEFAULT) ?? '');
        $password = trim(filter_input(INPUT_POST, 'password', FILTER_DEFAULT) ?? '');
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
        //strtolower

        $logs = $_GET['logs'] ?? [];

        if (empty($name)) {
            $logs['name'] = 'empty nombre';
        }

        if (empty($password)) {
            $logs['password'] = 'empty password';
        }

        if (empty($email)) {
            $logs['email_empty'] = 'empty email';
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $logs['email_invalid'] = 'invalid email';
        }

        if (!empty($logs)) {
            $query = http_build_query([
                'logs' => $logs,
            ]);

            header('Location: /register?' . $query);
            exit;
        }


        $usuario = new 
            Usuario(
                name: Encrypted::securedEncrypt(data: $name),
                password: Encrypted::hashPassword(password: $password),
                email: Encrypted::securedEncrypt(data: $email), 
                indexing_email: Encrypted::hashMessageAuthenticationCodeData(data: $email)
            );
        
        $usuario->insert();

        header("Location: /login");
        exit;
    }


}
