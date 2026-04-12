<?php
declare(strict_types=1);

namespace Root\Program\Mod;

use Root\Program\Mod\Habilidad;
use JsonSerializable;

abstract
class Personaje
implements JsonSerializable
{
    private string $nombre;
    private string $sprite;
    private int $position_x;
    private int $position_y;

    private int $vida;
    private int $mana;

    private array $habilidades;

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

        $this->habilidades = [];
    }

    public function addHabilidad(Habilidad $habilidad)
    {
        array_push($this->habilidades, $habilidad);
    }

    public function setVida(int $vida)
    {
        $this->vida = $vida; 
    }

    public function setMana(int $mana)
    {
        $this->mana = $mana; 
    }


    public function jsonSerialize(): mixed
    {
        return [
            'nombre' => $this->nombre, 
            'sprite' => $this->sprite, 
            'position_x' => $this->position_x, 
            'position_y' => $this->position_y, 
            'vida' => $this->vida, 
            'mana' => $this->mana,
            'habilidades' => array_map(
                fn($habilidad) => $habilidad->jsonSerialize(),
                $this->habilidades
            )
        ];
    }
}
