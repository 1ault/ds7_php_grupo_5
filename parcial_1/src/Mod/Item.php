<?php
declare(strict_types=1);

namespace Root\Program\Mod;

abstract class Item
{
    private string $nombre;
    private int $peso;

    public function __construct
    (string $nombre, int $peso) 
    {
        $this->nombre = $nombre;
        $this->peso = $peso;
    }

    public function getNombre(): string
    {
        return $this->nombre; 
    }

    public function getPeso(): int
    {
        return $this->peso; 
    }
}
