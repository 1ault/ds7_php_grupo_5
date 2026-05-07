<?php
class Colaborador{
    public $nombre;
    protected $salarioBase;
    private $identificacion;

    public function __construct($nombre, $salarioBase, $identificacion)
    {
        $this->nombre = $nombre;
        $this->salarioBase = $salarioBase;
        $this->identificacion = $identificacion;
    }

    public function getIdentificacion()
    {
        return $this->identificacion;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getSalarioBase()
    {
        return $this->salarioBase;
    }
}

// Ahora sí puedes crear objetos correctamente
$colaboradorTC = new Colaborador("Juan", 1000, "123");
$colaboradorC = new Colaborador("Ana", 800, "456");
$colaboradorH = new Colaborador("Luis", 10, "789");