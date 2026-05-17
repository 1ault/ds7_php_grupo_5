<?php

require_once __DIR__ . "/../Controlador/UsuarioController.php";

$controller = new UsuarioController();

$controller->registrar();

$mensaje = $controller->mensaje;

require_once __DIR__ . "/../Vista/registro.php";