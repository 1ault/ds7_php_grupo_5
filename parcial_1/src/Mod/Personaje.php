<?php
declare(strict_types=1);

namespace Root\Program\Mod;

use JsonSerializable;

abstract
class Personaje
implements JsonSerializable
{
    private string $nombre;
    private string $sprite;
    private int $position_x;
    private int $position_y;

    public function __construct
        (
            string $nombre, 
            string $sprite, 
            int $position_x,
            int $position_y
        ) 
    {
        $this->nombre = $nombre;
        $this->sprite = $sprite;
        $this->position_x = $position_x;
        $this->position_y = $position_y;
    }


    public function jsonSerialize(): mixed
    {
        return [
            'nombre' => $this->nombre, 
            'sprite' => $this->sprite, 
            'position_x' => $this->position_x, 
            'position_y' => $this->position_y, 
        ];
    }
}
