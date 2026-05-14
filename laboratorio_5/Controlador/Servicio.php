<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Modelo\Servicio as ServicioModelo;
use Root\Program\Utils\CrossSiteRequestForgery;

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

        $servicios = ServicioModelo::listar();

        ob_start();
        require_once PATH_ROOT_VISTA . '/Servicio.php';
        $main = ob_get_clean();


        // var_dump($_SESSION);
        $title = 'servicio';
    
        require_once PATH_ROOT_VISTA_LAYOUT . '/Main.php';
        exit;
    }

    public static function apiBuy()
    {
        session_write_close();

        if (!isset($_SESSION['user_auth']) || $_SESSION['user_auth'] !== true) {
            header('Location: login');
            exit;
        }

        if (!CrossSiteRequestForgery::tokenValidate($_POST['csrf_token']))
        {
            session_unset();
            session_destroy();
            header('Location: login');
            exit;
        }

        $selected = $_POST['services'] ?? [];
        var_dump($selected);
        var_dump(ServicioModelo::pedir($selected)); 

        //header('Location: /home');
        //exit;
    }

}
