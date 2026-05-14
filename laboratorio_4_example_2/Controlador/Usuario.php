<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

class Usuario
{

    public static function home()
    {
        $login = $_COOKIE['login'] ?? "";

        ob_start();
        require_once PATH_ROOT_VISTA . '/Bienvenida.php';
        $main = ob_get_clean();
        
        $title = 'home';
    
        require_once PATH_ROOT_VISTA_LAYOUT . '/Main.php';
    }

    public static function formulario()
    {     
        ob_start();
        require_once PATH_ROOT_VISTA . '/Formulario.php';
        $main = ob_get_clean();

        $title = 'formulario';
        
        require_once PATH_ROOT_VISTA_LAYOUT . '/Main.php';
    }

}
