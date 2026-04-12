<?php
declare(strict_types=1);

namespace Root\Program\Mod\Personaje;

use Root\Program\Mod\Personaje;

class Cangrejo
extends Personaje
{
    public function __construct() 
    {
        $nombre = "kangre";
        $sprite = "/assets/img/kangre.webp";
        $position_x = 200;
        $position_y = 200;

        parent::__construct($nombre, $sprite, $position_x, $position_y);
    }
}
