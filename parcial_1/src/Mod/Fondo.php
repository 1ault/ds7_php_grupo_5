<?php
declare(strict_types=1);

namespace Root\Program\Mod;

use JsonSerializable;

class Fondo implements JsonSerializable
{
    private string $nombre;
    private string $sprite;

    public function __construct($nombre, $sprite) 
    {
        $this->nombre = $nombre;
        $this->sprite = $sprite;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'nombre' => $this->nombre, 
            'sprite' => $this->sprite, 
        ];
    }
}
