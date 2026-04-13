<?php
declare(strict_types=1);

namespace Root\Program\Mod\Personaje\Enemigo;

use Root\Program\Mod\Personaje;
use Root\Program\Mod\Habilidad;
use Root\Program\Mod\Habilidad\CorteLimpio;

class Cangrejo
extends Personaje
{
    public function __construct() 
    {
        $nombre = "kangre";
        $sprite = "/assets/img/kangre.webp";
        $position_x = 340;
        $position_y = 340;

        parent::__construct($nombre, $sprite, $position_x, $position_y);

        $this->setVida(150);
        $this->setMana(80);

        $this->addHabilidad(new CorteLimpio());
    }
}
