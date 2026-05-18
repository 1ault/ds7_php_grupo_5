<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Root\Program\Controlador\UsuarioController;

UsuarioController::vistaRegistrar();



use Root\Program\Routers\Web;
use Root\Program\Routers\Api;

Web::run();
