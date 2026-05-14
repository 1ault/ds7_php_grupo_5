<?php
declare(strict_types=1);

namespace Root\Program\Routers;

use Root\Program\Controlador\Auth;
use Root\Program\Controlador\Servicio;


class Web
{
    private string $uri;

    public function __construct()
    {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 
    }

    public function run(): void
    {
        switch ($this->uri)
        {
            case '':
                Auth::viewLogin();
                exit;
            case '/':
                Auth::viewLogin();
                exit;
            case '/login':
                Auth::viewLogin();
                exit;
            case '/register':
                Auth::viewRegistro();
                exit;
            case '/home':
                Servicio::viewHome();
                exit;
            default:
                break;
        }

    }
    
}
