<?php
declare(strict_types=1);

namespace Root\Program\Mod;

abstract class Item
{
    private string $nombre;
    private float  $peso;
    private string $tipo;   // "pocion" | "arma"
    private float  $valor;  // precio en monedas
    private float  $efecto; // cuánto cura o cuánto daño añade

    public function __construct(
        string $nombre,
        float  $peso,
        string $tipo,
        float  $valor,
        float  $efecto
    ) {
        $this->nombre = $nombre;
        $this->peso   = $peso;
        $this->tipo   = $tipo;
        $this->valor  = $valor;
        $this->efecto = $efecto;
    }

    // Getters
    public function get_nombre(): string { return $this->nombre; }
    public function get_peso(): float    { return $this->peso; }
    public function get_tipo(): string   { return $this->tipo; }
    public function get_valor(): float   { return $this->valor; }
    public function get_efecto(): float  { return $this->efecto; }
}