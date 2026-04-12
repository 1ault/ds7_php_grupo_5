<?php
declare(strict_types=1);

namespace Root\Program\Mod;

use JsonSerializable;

abstract 
class Habilidad
implements JsonSerializable
{
    private string $nombre;
    private int $coste;
    private int $dano_base;
    private string $descripcion;

    public function __construct(
        string $nombre,
        int $coste,
        int $dano_base,
        string $descripcion
    ) {
        $this->nombre = $nombre;
        $this->coste = $coste;
        $this->dano_base = $dano_base;
        $this->descripcion = $descripcion;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "nombre" => $this->nombre,
            "coste" => $this->coste,
            "dano_base" => $this->dano_base,
            "descripcion" => $this->descripcion
        ];
    }
}
