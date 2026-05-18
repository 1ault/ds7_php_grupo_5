<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Root\Program\Controlador\AspiranteController;

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

AspiranteController::guardar();
