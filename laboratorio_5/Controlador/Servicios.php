<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Modelo\Usuario;
use Root\Program\Modelo\Libro;

class Servicio
{

    public static function login()
    {
        session_start();

        $logs = $_GET['logs'] ?? [];

        $usuario_login = new Usuario(-1, $_POST['nombre'], $_POST['contrasena'])

        $usuario_login->login();

        if (!empty($usuario_login)) 
        {
            $_SESSION["nombre"] = $_POST['id'];
            $_SESSION["nombre"] = $_POST['nombre'];

            $nombre = $_SESSION["nombre"]; 
        }

        ob_start();
        require_once PATH_ROOT_VISTA . '/Login.php';
        $main = ob_get_clean();

        $title = 'login';
        
        require_once PATH_ROOT_VISTA_LAYOUT . '/Main.php';
    }

    public static function registro()
    {     
 
        ob_start();
        require_once PATH_ROOT_VISTA . '/Registro.php';
        $main = ob_get_clean();

        $title = 'registro';
        
        require_once PATH_ROOT_VISTA_LAYOUT . '/Main.php';
    }

    public static function usuario_registro()
    {     
        
        $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
        $contrasena = trim(filter_input(INPUT_POST, 'contrasena', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
        $logs = $_GET['logs'] ?? [];

        if (empty($nombre)) {
            $logs['nombre'] = 'empty nombre';
        }

        if (empty($contrasena)) {
            $logs['nombre'] = 'empty nombre';
        }

        if (!empty($logs)) {
            $query = http_build_query([
                'logs' => $logs,
            ]);

            header('Location: /registro?' . $query);
            exit;
        }

        $usuario = new 
            Usuario(
                -1, 
                $nombre, 
                $contrasena
            )
        
        $usuario->insert();

        header("Location: /login");
        exit;
    }

    public static function servicio()
    {
        session_start();
        
        $logs = $_GET['logs'] ?? [];

        ob_start();
        require_once PATH_ROOT_VISTA . '/Servicio.php';
        $main = ob_get_clean();


        $title = 'servicio';
    
        require_once PATH_ROOT_VISTA_LAYOUT . '/Main.php';
    }

}
