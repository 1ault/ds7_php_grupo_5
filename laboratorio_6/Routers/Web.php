<?php
declare(strict_types=1);

namespace Root\Program\Routers;

use Root\Program\Controlador\FormControlador;
use Root\Program\Utils\Http;
use Root\Program\Utils\HttpStatus;

class Web
{
    public static function run(): void
    {
        switch (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
        {
            // ── Form ─────────────────────────────────────────────
            case '':
            case '/':
            case '/form':
                FormControlador::vista();
                exit;

            case '/post/form/guardar':
                FormControlador::guardar();
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
