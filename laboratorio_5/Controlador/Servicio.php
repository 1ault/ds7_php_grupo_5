<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

class Servicio
{

    public static function viewHome()
    {
        session_write_close();

        if (!isset($_SESSION['user_auth']) || $_SESSION['user_auth'] !== true) {
            header('Location: login');
            exit;
        }

        $logs = $_GET['logs'] ?? [];

        ob_start();
        require_once PATH_ROOT_VISTA_LAYOUT . '/Header.php';
        $header = ob_get_clean();


        $servicios = [
            1 => ['name' => 'Mantenimiento de computadoras', 'precio' => 2500],
            2 => ['name' => 'Instalacion de software', 'precio' => 1500],
            3 => ['name' => 'Respaldo de informacion', 'precio' => 1000],
            4 => ['name' => 'Limpieza interna de hardware', 'precio' => 2000],
            5 => ['name' => 'REvision de red y conexion', 'precio' => 3000],
        ];



        ob_start();
        require_once PATH_ROOT_VISTA . '/Servicio.php';
        $main = ob_get_clean();


        // var_dump($_SESSION);
        $title = 'servicio';
    
        require_once PATH_ROOT_VISTA_LAYOUT . '/Main.php';
    }

    public static function apiBuy()
    {
    }

}
