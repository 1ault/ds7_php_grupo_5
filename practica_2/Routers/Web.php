<?php
declare(strict_types=1);

namespace Root\Program\Routers;

use Root\Program\Controlador\LibroController;

enum HttpStatus: int {
    case OK = 200;
    case CREATED = 201;
    case BAD_REQUEST = 400;
    case NOT_FOUND = 404;
    case INTERNAL_SERVER_ERROR = 500;
}

class Web
{
    private string $uri;

    public function __construct()
    {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 
    }

    static public function response(string $data, HttpStatus $status): void
    {
        http_response_code($status->value);
        echo htmlspecialchars($data);
        exit;
    }

    public function run(): void
    {

        switch ($this->uri)
        {
            case '/':
                LibroController::home();
                break;
            case '/listar';
                LibroController::listar();
                break;
            case '/editar';
                LibroController::editar();
                break;
            case '/crear';
                LibroController::crear();
                break;
            case '/eliminar';
                LibroController::eliminar();
                break;
            case '/libro/crear';
                LibroController::libro_crear();
                break;
            case '/libro/editar';
                LibroController::libro_editar();
                break;
            case '/libro/eliminar';
                LibroController::libro_eliminar();
                break;
            default:
                self::response(
                    "404 - Not Found",
                    HttpStatus::NOT_FOUND
                );
                break;
        }

    }
    
}
