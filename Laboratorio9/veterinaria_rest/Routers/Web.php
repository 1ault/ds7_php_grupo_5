<?php
declare(strict_types=1);

namespace Root\Program\Routers;

use Root\Program\controllers\PedidosController;
use Root\Program\Utils\Http;
use Root\Program\Utils\HttpStatus;

class Web
{
    public static function run(): void
    {
        switch (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
        {
            case '':
            case '/':
                PedidosController::vistatest();
                exit;

                case '/facturar':
                PedidosController::facturar();
                exit;

                case '/resumen':
                PedidosController::resumen();
                exit;

                case '/test':
                PedidosController::vistatest();
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