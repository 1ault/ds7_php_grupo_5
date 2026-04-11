<?php
declare(strict_types=1);

namespace Root\Program\Mod;

abstract class Habilidad
{
    private string $nombre;
    private float $coste;
    private float $dano_base;

    public function __construct
    () 
    {
    }
}
