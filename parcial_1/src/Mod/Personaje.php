<?php
declare(strict_types=1);

namespace Root\Program\Mod;

abstract class Personaje
{
    private string $nombre;
    private string $image;

    private float $vida;
    private float $tiempo;

    private float $mana;
    private float $energia;

    private float $suerte;
    private float $velocidad;
    private float $resistencia;

    private array $inventario

    private float $experiencia;
    private array $habilidades;

    private array $profesiones;

    public function __construct
        (string $nombre) 
    {
        $this->nombre = $nombre;
    }

    public function add_habilidad
        (Habilidad $habilidad): void
    {

    }

    public function sub_vida
        (float $vida_sub): void
    {
        $this->vida -= $vida_sub;
    }

    public function get_nombre(): string
    {
        return $this->nombre;
    }
}
