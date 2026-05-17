<?php
require_once __DIR__ .
"/../Controlador/LoginController.php";

$controller = new LoginController();

$controller->login();

$mensaje = $controller->mensaje;

require_once __DIR__ ."/../Vista/login.php";
