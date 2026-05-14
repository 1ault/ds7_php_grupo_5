<?php
declare(strict_types=1);

namespace Root\Program\Mod\Personaje;

use Root\Program\Mod\Personaje;
use Root\Program\Mod\Habilidad\HabilidadNormal;
use Root\Program\Mod\Habilidad\HabilidadEspecial;

class Cangrejo extends Personaje
{
    public function __construct()
    {
        parent::__construct('Cangrejo', '/assets/img/kangre.webp');

        // Stats únicos — alta resistencia, daño físico alto

        $this->add_habilidad(new HabilidadNormal(
            nombre:    'Corte Limpio',
            coste:     20.0,
            dano_base: 35.0
        ));

        $this->add_habilidad(new HabilidadEspecial(
            nombre:               'Pinza Aplastante',
            coste:                40.0,
            dano_base:            55.0,
            prob_critico:         0.35, // más chance de crítico
            multiplicador_critico: 2.0
        ));
    }

    // Pasiva: reduce el daño recibido un 10%
    public function pasiva(float $dano_entrante): float
    {
        return $dano_entrante * 0.9;
    }
}