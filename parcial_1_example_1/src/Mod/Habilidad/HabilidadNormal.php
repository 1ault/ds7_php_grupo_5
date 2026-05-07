<?php
declare(strict_types=1);

namespace Root\Program\Mod\Habilidad;

use Root\Program\Mod\Habilidad;

class HabilidadNormal extends Habilidad
{
    public function __construct(
        string  $nombre,
        float   $coste,
        float   $dano_base,
        float   $prob_critico          = 0.2,
        float   $multiplicador_critico = 1.5,
        ?string $efecto_estado         = null
    ) {
        parent::__construct(
            $nombre,
            $coste,
            $dano_base,
            $prob_critico,
            $multiplicador_critico,
            $efecto_estado
        );
    }
}