<?php
declare(strict_types=1);
namespace Tuzz\Laboratorio21;
require_once __DIR__ . '/Colaboradores.php';


class ColaboradorPorHora 
extends Colaboradores
{
    private float $tarifa;
    private float $horas;

    public function __construct
    ( 
        string $nombre, 
        string $apellido, 
        string $id,
        float $tarifa,
        float $horas
    )
    {
        parent::__construct($nombre, $apellido, $id, 0);
        $this->tarifa = $tarifa;
        $this->horas = $horas;
    }

    public function setTarifa(float $tarifa)
    {
        $this->tarifa = $tarifa;
    }

    public function setHoras(float $horas)
    {
        $this->horas = $horas;
    }

    public function calcularSalario(): float
    {
        return $this->tarifa * $this->horas;
    }

}
