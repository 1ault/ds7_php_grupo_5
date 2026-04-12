<?php
declare(strict_types=1);

namespace Root\Program\Mod\Personaje;

use Root\Program\Mod\Personaje;
use Root\Program\Mod\Habilidad\HabilidadNormal;
use Root\Program\Mod\Habilidad\HabilidadEspecial;

class Axolote extends Personaje
{
    public function __construct()
    {
        parent::__construct('Axolote', '/assets/img/axolote.webp');

        // Stats únicos — balanceado, buen mana

        $this->add_habilidad(new HabilidadNormal(
            nombre:    'Burbuja',
            coste:     10.0,
            dano_base: 20.0
        ));

        $this->add_habilidad(new HabilidadEspecial(
            nombre:        'Torrente',
            coste:         30.0,
            dano_base:     45.0,
            efecto_estado: 'quemadura' // "quemadura de frío"
        ));
    }

    // Pasiva: al subir de nivel recupera mana completo
    public function pasiva(): string
    {
        // Se llama desde subir_nivel()
        return "{$this->get_nombre()} recuperó todo el mana (pasiva)";
    }
}