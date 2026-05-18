<?php

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: loginP.php");
    exit;
}

require_once __DIR__ . "/../Controlador/AspiranteController.php";
require_once __DIR__ . "/../Modelo/Aspirante.php";

$controller = new AspiranteController();

$controller->procesar();

$mensaje = $controller->mensaje;

$modelo = new Aspirante();

$datosAspirante = $modelo->obtenerPorUsuarioId(
    $_SESSION["usuario_id"]
);

if ($datosAspirante) {
    require_once __DIR__ . "/../Vista/actualizarFormulario.php";
} else {
    require_once __DIR__ . "/../Vista/formulario.php";
}