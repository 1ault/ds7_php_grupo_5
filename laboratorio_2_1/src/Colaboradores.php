<?php
declare(strict_types=1);
namespace Tuzz\Laboratorio21;
require_once __DIR__ . '/Colaboradores.php';


abstract class Colaboradores
{
    protected string $nombre;
    protected string $apellido;
    protected float $salario_base;
    protected string $id;

    public function __construct
    (
        string $nombre, 
        string $apellido, 
        string $id,
        float $salario_base, 
    ) 
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->id = $id;
        $this->salario_base = $salario_base;
    }

    public function mostrarInformacion(): string
    {
        return sprintf("nombre:%s apellido:%s id:%s", $this->nombre, $this->apellido, $this->id);
    }

    abstract public function calcularSalario(): float;

}
