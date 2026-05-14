<?php
declare(strict_types=1);

namespace Root\Program\Mod\Habilidad;

use Root\Program\Mod\Habilidad;

class PinzaAplastante
extends Habilidad
{
    public function __construct() 
    {
        $nombre = "Pinza Aplastante";
        $coste = 10;
        $dano_base = 10;
        $descripcion = "";

        parent::__construct($nombre, $coste, $dano_base, $descripcion);
    }
}
