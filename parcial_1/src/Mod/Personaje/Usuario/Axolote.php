<?php
declare(strict_types=1);

namespace Root\Program\Mod\Personaje\Usuario;

use Root\Program\Mod\Personaje;

class Axolote
extends Personaje
{
    
    public function __construct
    () 
    {
        $this->nombre = "jojo";
        $this->sprite = "/assets/img/jojo.webp";
        $this->position_x = 200;
        $this->position_y = 200;

        parent::__construct($nombre, $sprite, $position_x, $position_y);

        $this->setVida(100);
        $this->setMana(200);

    }
}
