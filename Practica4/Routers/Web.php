<?php
declare(strict_types=1);

namespace Root\Program\Routers;

use Root\Program\Controlador\UsuarioController;
use Root\Program\Controlador\TareasController;
use Root\Program\Utils\Http;
use Root\Program\Utils\HttpStatus;

class Web
{
    public static function run(): void
    {
        switch (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
        {
            // ── Raíz ───────────────────────────────────────────────────────
            case '':
            case '/':
                UsuarioController::vistaRegistro();
                exit;

            // ── Registro ───────────────────────────────────────────────────
            case '/registro':
                UsuarioController::vistaRegistro();
                exit;
            case '/post/usuario/registro':
                UsuarioController::postRegistro();
                exit;

            // ── Login / Logout ─────────────────────────────────────────────
            case '/login':
                UsuarioController::vistaLogin();
                exit;
            case '/post/usuario/login':
                UsuarioController::postLogin();
                exit;
            case '/post/usuario/logout':
                UsuarioController::postLogout();
                exit;

            // ── Tareas ──────────────────────────────────────────────────
            case '/tareas':
                TareasController::vistaTareas();
                exit;   
            case '/post/tarea/guardar':
                TareasController::postGuardarTarea();
                exit;
            case '/post/tarea/eliminar':
                TareasController::EliminarTarea();
                exit;
            case '/post/tarea/editar':
                TareasController::EditarTarea();
                exit;

            // ── 404 ────────────────────────────────────────────────────────
            default:
                Http::response(
                    ["404 - Not Found"],
                    HttpStatus::NOT_FOUND
                );
                exit;
        }
    }
}
