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
    }
}
