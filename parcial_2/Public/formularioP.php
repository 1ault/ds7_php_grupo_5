<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: loginP.php");
    exit;
}

require_once __DIR__ . "/../Controlador/AspiranteController.php";

$controller = new AspiranteController();

$controller->guardar();

$mensaje = $controller->mensaje;

require_once __DIR__ . "/../Vista/formulario.php";