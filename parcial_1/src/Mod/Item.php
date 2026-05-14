<?php
declare(strict_types=1);

namespace Root\Program\Mod;

enum ItemType: string
{
    case Arma = 'arma';
    case Consumible = 'consumible';
}

abstract class Item
{
    private string $nombre;
<<<<<<< HEAD
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
=======
    private ItemType $item_type;
    private int $peso;
    private string $descripcion;

    public function __construct
    (
        string $nombre, 
        ItemType $item_type, 
        int $peso, 
        string $descripcion
    ) 
    {
        $this->nombre = $nombre;
        $this->item_type = $item_type;
        $this->peso = $peso;
        $this->descripcion = $descripcion;
    }

    public function getItemType(): string
    {
        return $this->item_type->value; 
    }

    public function getDescripcion(): string
    {
        return $this->descripcion; 
    }

    public function getNombre(): string
    {
        return $this->nombre; 
    }

    public function getPeso(): int
    {
        return $this->peso; 
>>>>>>> 324c2646896017040376d9eb086b34de89808e33
    }

    // Getters
    public function get_nombre(): string { return $this->nombre; }
    public function get_peso(): float    { return $this->peso; }
    public function get_tipo(): string   { return $this->tipo; }
    public function get_valor(): float   { return $this->valor; }
    public function get_efecto(): float  { return $this->efecto; }
}