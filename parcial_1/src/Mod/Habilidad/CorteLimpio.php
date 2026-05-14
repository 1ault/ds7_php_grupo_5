<?php
declare(strict_types=1);

namespace Root\Program\Mod\Habilidad;

use Root\Program\Mod\Habilidad;

class CorteLimpio
extends Habilidad
{
    public function __construct() 
    {
        $nombre = "Corte Limpio";
        $coste = 0;
        $dano_base = 5;
        $descripcion = "";

        parent::__construct($nombre, $coste, $dano_base, $descripcion);
    }
}

