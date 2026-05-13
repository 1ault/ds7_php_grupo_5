<?php
declare(strict_types=1);

namespace Root\Program\Routers;

use Root\Program\Controlador\Auth;
use Root\Program\Controlador\Servicio;
use Root\Program\Utils\Http;
use Root\Program\Utils\HttpStatus;

class Api
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

            case '/api/auth/login':
                Auth::apiLogin();
                break;
            case '/api/auth/register':
                Auth::apiRegistro();
                break;
            case '/api/servicio/buy':
                break;
            default:
                Http::response(
                    "404 - Not Found",
                    HttpStatus::NOT_FOUND
                );
                break;
        }

    }
    
}
