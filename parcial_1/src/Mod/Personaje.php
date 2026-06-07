<?php
declare(strict_types=1);

namespace Root\Program\Mod;

use Root\Program\Mod\Habilidad;
use Root\Program\Mod\Item;
use Root\Program\Mod\Type\Vector2DInt;

use JsonSerializable;

abstract
class Personaje
implements JsonSerializable
{
    private string $nombre;
    private string $sprite;
    private int $position_x;
    private int $position_y;
    private Vector2DInt $vector_2d_int;

    private int $vida;
    private int $mana;
    private int $xp;

    private array $habilidades;
    private array $inventario;
    private array $estados;

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
        $this->xp = 0;

        $this->habilidades = [];
        $this->inventario = [];
        $this->estados = [];
    }


    public function addItem(Item $item)
    {
        array_push($this->inventario, $item);
    }

    public function addHabilidad(Habilidad $habilidad)
    {
        array_push($this->habilidades, $habilidad);
    }

    public function addXP(int $xp)
    {
        $this->xp = $this->xp + $xp; 
    }


    public function addVida(int $vida)
    {
        $this->vida = $this->vida + $vida; 
    }


    public function addMana(int $mana)
    {
        $this->mana = $this->mana + $mana; 
    }


    public function subVida(int $vida)
    {
        $this->vida = $this->vida - $vida; 
    }

    public function subMana(int $mana)
    {
        $this->mana = $this->mana - $mana; 
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
            'xp' => $this->xp,
            'habilidades' => array_map(
                fn($habilidad) => $habilidad->jsonSerialize(),
                $this->habilidades
            )
        ];
    }
}
