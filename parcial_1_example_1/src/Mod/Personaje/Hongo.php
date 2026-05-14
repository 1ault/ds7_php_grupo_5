<?php
declare(strict_types=1);

namespace Root\Program\Mod\Personaje;

use Root\Program\Mod\Personaje;
use Root\Program\Mod\Habilidad\HabilidadNormal;
use Root\Program\Mod\Habilidad\HabilidadEspecial;

class Hongo extends Personaje
{
    public function __construct()
    {
        parent::__construct('Hongo', '/assets/img/hongo.webp');

        // Stats únicos — más vida, menos velocidad
        // Pasiva: regenera 5 de vida cada turno

        $this->add_habilidad(new HabilidadNormal(
            nombre:     'Espora',
            coste:      15.0,
            dano_base:  25.0
        ));

        $this->add_habilidad(new HabilidadEspecial(
            nombre:          'Espora Venenosa',
            coste:           35.0,
            dano_base:       40.0,
            efecto_estado:   'veneno'
        ));
    }

    // Pasiva: regeneración al inicio de cada turno
    public function pasiva(): string
    {
        $this->add_vida(5.0);
        return "{$this->get_nombre()} regeneró 5 de vida (pasiva)";
    }
}