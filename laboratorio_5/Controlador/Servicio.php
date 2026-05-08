<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

class Servicio
{

    public static function viewHome()
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
