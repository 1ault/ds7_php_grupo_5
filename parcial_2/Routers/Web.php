<?php
declare(strict_types=1);

namespace Root\Program\Routers;

use Root\Program\Controlador\UsuarioController;
use Root\Program\Controlador\LoginController;
use Root\Program\Controlador\AspiranteController;
use Root\Program\Controlador\AdminController;
use Root\Program\Utils\Http;
use Root\Program\Utils\HttpStatus;

class Web
{
    public static function run(): void
    {
        switch (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
        {
            case '':
                UsuarioController::vistaRegistro();
                exit;
            case '/':
                UsuarioController::vistaRegistro();
                exit;


            case '/registro':
                UsuarioController::vistaRegistro();
                exit;
            case '/post/usuario/registro':
                UsuarioController::postRegistro();
                exit;


            case '/login':
                UsuarioController::vistaLogin();
                exit;
            case '/post/usuario/login':
                UsuarioController::postLogin();
                exit;


            case '/aspirante':
                AspiranteController::vistaAspirante();
                exit;
            case '/post/aspirante/guardar':
                AspiranteController::postGuardarAspirante();
                exit;
            case '/post/aspirante/update':
                AspiranteController::postUpdateAspirante();
                exit;


            case '/admin':
                AdminController::vistaAdmin();
                exit;
            case '/post/admin/update':
                AdminController::postAdminUpdateAspirante();
                exit;
            case '/api/admin/update':
                AdminController::apiAdminUpdateAspirante();
                exit;

            default:
                Http::response(
                    ["404 - Not Found"],
                    HttpStatus::NOT_FOUND
                );
                exit;
        }

    }
    
}
