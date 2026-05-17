<?php
<<<<<<< Updated upstream
=======
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Root\Program\Controlador\UsuarioController;

$controller = new UsuarioController();

$controller->registrar();

$mensaje = $controller->mensaje;

require_once __DIR__ . "/../Vista/Registro.php";
>>>>>>> Stashed changes
