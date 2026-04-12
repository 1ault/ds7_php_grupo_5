<?php
declare(strict_types=1);

namespace Root\Program\Mod;

class Inventario
{
    private array $items = [];
    private float $monedas;

    public function __construct(float $monedas = 200.0)
    {
        $this->monedas = $monedas;
    }

    public function comprar(Item $item): bool
    {
        if ($this->monedas < $item->get_valor()) {
            return false; // no alcanza
        }
        $this->monedas -= $item->get_valor();
        $this->items[]  = $item;
        return true;
    }

    public function usar_pocion(): ?float
    {
        foreach ($this->items as $key => $item) {
            if ($item->get_tipo() === 'pocion') {
                $efecto = $item->get_efecto();
                unset($this->items[$key]);
                $this->items = array_values($this->items);
                return $efecto; // retorna cuánto cura
            }
        }
        return null; // no hay pociones
    }

    public function get_bonus_ataque(): float
    {
        $bonus = 0.0;
        foreach ($this->items as $item) {
            if ($item->get_tipo() === 'arma') {
                $bonus += $item->get_efecto();
            }
        }
        return $bonus;
    }

    public function limpiar(): void
    {
        $this->items = []; // se llama entre rondas
    }

    // Getters
    public function get_items(): array   { return $this->items; }
    public function get_monedas(): float { return $this->monedas; }
    public function add_monedas(float $cantidad): void
    {
        $this->monedas += $cantidad;
    }
}