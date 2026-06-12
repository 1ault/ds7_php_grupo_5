<?php
declare(strict_types=1);

namespace Root\Program\Routers;

use Root\Program\Controlador\UsuarioController;
use Root\Program\Controlador\PeliculaController;
use Root\Program\Controlador\PreferenciaController;
use Root\Program\Controlador\AdminController;
use Root\Program\Utils\Http;
use Root\Program\Utils\HttpStatus;


class Api
{
    public static function run(string $uri): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
            exit;
        
        switch ($uri)
        {
            // ── API: listar / filtrar (JSON) ───────────────────────────────
            case '/api/peliculas':
                PeliculaController::apiListar();
                exit;

            // ── API: exportar XML ──────────────────────────────────────────
            case '/api/peliculas/xml':
                PeliculaController::apiExportarXml();
                exit;


            // ── Admin ──────────────────────────────────────────────────────
            case '/api/admin/importar/xml':
                AdminController::apiImportarXml();
                exit;

            case '/api/admin/importar/json':
                AdminController::apiImportarJson();
                exit;
            
            // ── 404 ────────────────────────────────────────────────────────
            default:
                http_response_code(404);
                echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">
                      <title>404 — CineMatch</title>
                      <style>body{font-family:Arial,sans-serif;text-align:center;padding:60px;background:#0f0f1a;color:#e8e8f0;}
                      h1{color:#e50914;font-size:72px;margin:0}p{font-size:18px;color:#aaa}
                      a{color:#4d9fff;font-size:16px}</style></head>
                      <body><h1>404</h1><p>Página no encontrada.</p>
                      <a href="/semestral/Public/">← Volver al inicio</a></body></html>';
                exit;
        }
    }
}
