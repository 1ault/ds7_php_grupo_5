<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Root\Program\Controlador\LoginController;

$controller = new LoginController();

$controller->login();

$mensaje = $controller->mensaje;

require_once __DIR__ ."/../Vista/login.php";
