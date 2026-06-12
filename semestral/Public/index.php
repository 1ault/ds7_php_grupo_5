<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Root\Program\Routers\Web;
use Root\Program\Routers\Api;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

Web::page(uri: $uri);
Web::post(uri: $uri);
Api::post(uri: $uri);

