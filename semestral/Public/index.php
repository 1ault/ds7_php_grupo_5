<?php
declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

use Root\Program\Routers\Web;
use Root\Program\Routers\Api;

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$method = $_SERVER["REQUEST_METHOD"];

if ($method === "POST" && str_starts_with($uri, "/api/")) {
    Api::run($uri);
} elseif ($method === "POST") {
    Web::post($uri);
} else {
    Web::page($uri);
}

// ── 404 ────────────────────────────────────────────────────────
http_response_code(404);
echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">
      <title>404 — CineMatch</title>
      <style>body{font-family:Arial,sans-serif;text-align:center;padding:60px;background:#0f0f1a;color:#e8e8f0;}
      h1{color:#e50914;font-size:72px;margin:0}p{font-size:18px;color:#aaa}
      a{color:#4d9fff;font-size:16px}</style></head>
      <body><h1>404</h1><p>Página no encontrada.</p>
      <a href="/">← Volver al inicio</a></body></html>';
exit();
