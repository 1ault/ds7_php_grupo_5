<?php
declare(strict_types=1);

namespace Root\Program\Mod;

abstract class Habilidad
{
    private string $nombre;
    private float  $coste;
    private float  $dano_base;
    private float  $prob_critico;
    private float  $multiplicador_critico;
    private ?string $efecto_estado; // ej: "quemadura", "veneno", null

    public function __construct(
        string $nombre,
        float  $coste,
        float  $dano_base,
        float  $prob_critico          = 0.2,
        float  $multiplicador_critico = 1.5,
        ?string $efecto_estado        = null
    ) {
        $this->nombre                = $nombre;
        $this->coste                 = $coste;
        $this->dano_base             = $dano_base;
        $this->prob_critico          = $prob_critico;
        $this->multiplicador_critico = $multiplicador_critico;
        $this->efecto_estado         = $efecto_estado;
    }

    // Calcula el daño final (con posible crítico)
    public function calcular_dano(): array
    {
        $es_critico = (mt_rand() / mt_getrandmax()) < $this->prob_critico;
        $dano = $es_critico
            ? $this->dano_base * $this->multiplicador_critico
            : $this->dano_base;

        return [
            'dano'       => round($dano, 2),
            'es_critico' => $es_critico,
            'efecto'     => $this->efecto_estado,
        ];
    }

    // Getters
    public function get_nombre(): string  { return $this->nombre; }
    public function get_coste(): float    { return $this->coste; }
    public function get_dano_base(): float { return $this->dano_base; }
    public function get_efecto(): ?string { return $this->efecto_estado; }
}