<?php
declare(strict_types=1);
namespace Tuzz\Laboratorio21;
require_once __DIR__ . '/Colaboradores.php';


class ColaboradorPorTiempoCompleto
extends Colaboradores
{

    public function calcularSalario(): float
    {
        return $this->salario_base;
    }
}
