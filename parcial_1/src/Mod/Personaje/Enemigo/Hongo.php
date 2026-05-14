<?php
declare(strict_types=1);


namespace Root\Program\Mod\Personaje\Enemigo;

use Root\Program\Mod\Personaje;

class Hongo 
extends Personaje
{

    public function __construct() 
    {

        $nombre = "champi";
        $sprite = "/assets/img/enemigo/champi.webp";
        $position_x = 790;
        $position_y = -20;

        parent::__construct($nombre, $sprite, $position_x, $position_y);

        $this->setVida(120);
        $this->setMana(120);

    }
}

