<?php
declare(strict_types=1);

namespace Root\Program\Routers;

use Root\Program\Controlador\PeliculaController;
use Root\Program\Controlador\AdminController;

class Api
{
    public static function run(string $uri): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            exit();
        }

        switch ($uri) {
            // ── API: listar / filtrar (JSON) ───────────────────────────────
            case "/api/peliculas":
                PeliculaController::apiListar();
                exit();

            // ── API: exportar XML ──────────────────────────────────────────
            case "/api/peliculas/xml":
                PeliculaController::apiExportarXml();
                exit();

            // ── Admin ──────────────────────────────────────────────────────
            case "/api/admin/importar/xml":
                AdminController::apiImportarXml();
                exit();

            case "/api/admin/importar/json":
                AdminController::apiImportarJson();
                exit();

            default:
                break;
        }
    }
}
