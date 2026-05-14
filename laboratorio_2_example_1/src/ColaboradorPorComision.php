<?php
declare(strict_types=1);
namespace Tuzz\Laboratorio21;
require_once __DIR__ . '/Colaboradores.php';

class ColaboradorPorComision 
extends Colaboradores
{
    protected float $comision;

    public function __construct
    ( 
        string $nombre, 
        string $apellido, 
        string $id,
        float $salario_base,
        float $comision
    )
    {
        parent::__construct($nombre, $apellido, $id, $salario_base);
        $this->comision = $comision;
    }


    public function setComision(float $comision)
    {
        $this->comision = $comision;
    }

    public function calcularSalario(): float
    {
        return $this->salario_base + $this->comision;
    }
}
