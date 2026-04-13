<?php
declare(strict_types=1);

namespace Root\Program\Mod\Personaje\Usuario;

use Root\Program\Mod\Personaje;

class Hongo 
extends Personaje
{
    $this->nombre = "champi";
    $this->sprite = "/assets/img/champi.webp";
    $this->position_x = 200;
    $this->position_y = 200;

    parent::__construct($nombre, $sprite, $position_x, $position_y);

    $this->setVida(120);
    $this->setMana(120);
}

