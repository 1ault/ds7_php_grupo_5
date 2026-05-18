<?php
declare(strict_types=1);

namespace Root\Program\Routers;

use Root\Program\Controlador\Auth;
use Root\Program\Controlador\Servicio;

use Root\Program\Modelo\Usuario;


namespace Root\Program\Controlador;
class Web
{
    public static function run(): void
    {
        switch (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
        {
            case '':
                UsuarioController::vistaRegistrar();
                exit;
            case '/':
                UsuarioController::vistaRegistrar();
                exit;
            case '/register':
                UsuarioController::vistaRegistrar();
                exit;
            case '/login':
                LoginController::vistaLogin();
                exit;
            case '/':
                Servicio::viewHome();
                exit;
            default:
                break;
        }

    }
    
}
