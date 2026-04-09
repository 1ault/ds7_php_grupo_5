<?php
declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use Tuzz\Laboratorio21\ColaboradorPorComision;
use Tuzz\Laboratorio21\ColaboradorPorHora;
use Tuzz\Laboratorio21\ColaboradorPorTiempoCompleto;

// Grupo 5
// Jonathan Quinto
// Alexander Castroverde
// Abdias Ruedas
// Nadesh Valdes
// Whitney Ault
//
// https://github.com/1ault/ds7_php_grupo_5/tree/main/laboratorio_2_1 
//

$empleados = [
    new ColaboradorPorComision("jaz", "zaj", "1", 100, 5),
    new ColaboradorPorHora("gark", "yaz", "2", 50, 5),
    new ColaboradorPorTiempoCompleto("pear", "peal", "3", 100)
];

foreach ($empleados as $empleado) {
    $msg_info = $empleado->mostrarInformacion();
    $msg_salario = sprintf("%f", $empleado->calcularSalario());
    $msg_info_fix = htmlspecialchars($msg_info, ENT_QUOTES, "UTF-8");
	$msg_salario_fix = htmlspecialchars($msg_salario, ENT_QUOTES, "UTF-8");
    echo sprintf("%s salario: %s<br />", $msg_info_fix, $msg_salario_fix);
}



