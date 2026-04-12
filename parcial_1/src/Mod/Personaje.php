<?php
declare(strict_types=1);

namespace Root\Program\Mod;

abstract class Personaje
{
    private string $nombre;
    private string $image;

    private float $vida;
    private float $vida_max;

    private float $mana;
    private float $energia;

    private float $suerte;
    private float $velocidad;
    private float $resistencia;

    private float $experiencia;
    private int   $nivel;
    private float $exp_para_subir; // experiencia necesaria para el siguiente nivel

    private Inventario $inventario;

    private array $habilidades = [];
    private array $profesiones = [];

    // Efectos de estado activos: ['quemadura' => turnos_restantes]
    private array $efectos_activos = [];

    private float $tiempo;

    public function __construct(string $nombre, string $image = '')
    {
        $this->nombre         = $nombre;
        $this->image          = $image;
        $this->vida           = 100.0;
        $this->vida_max       = 100.0;
        $this->mana           = 100.0;
        $this->energia        = 100.0;
        $this->suerte         = 1.0;
        $this->velocidad      = 1.0;
        $this->resistencia    = 1.0;
        $this->experiencia    = 0.0;
        $this->nivel          = 1;
        $this->exp_para_subir = 100.0;
        $this->tiempo         = 0.0;
        $this->inventario     = new Inventario();
    }

    // --- Vida ---

    public function sub_vida(float $cantidad): string
    {
        $dano_real   = max(0.0, $cantidad - $this->resistencia);
        $this->vida  = max(0.0, $this->vida - $dano_real);

        $msg = "{$this->nombre} recibió {$dano_real} de daño. Vida restante: {$this->vida}";

        if (!$this->is_vivo()) {
            $msg .= "\n¡{$this->nombre} ha sido derrotado!";
        }

        return $msg;
    }

    public function add_vida(float $cantidad): void
    {
        $this->vida = min($this->vida_max, $this->vida + $cantidad);
    }

    public function is_vivo(): bool
    {
        return $this->vida > 0.0;
    }

    // --- Habilidades ---

    public function add_habilidad(Habilidad $habilidad): string
    {
        $this->habilidades[] = $habilidad;
        return "{$this->nombre} aprendió: {$habilidad->get_nombre()}";
    }

    public function usar_habilidad(string $nombre, Personaje $objetivo): string
    {
        // Buscar habilidad
        $habilidad = null;
        foreach ($this->habilidades as $h) {
            if ($h->get_nombre() === $nombre) {
                $habilidad = $h;
                break;
            }
        }

        if ($habilidad === null) {
            return "{$this->nombre} no tiene la habilidad '{$nombre}'";
        }

        if ($this->mana < $habilidad->get_coste()) {
            return "{$this->nombre} no tiene suficiente mana";
        }

        // Consumir mana
        $this->mana -= $habilidad->get_coste();

        // Calcular daño + bonus de armas
        $resultado = $habilidad->calcular_dano();
        $dano      = $resultado['dano'] + $this->inventario->get_bonus_ataque();

        $msg = $objetivo->sub_vida($dano);

        if ($resultado['es_critico']) {
            $msg = "💥 ¡Golpe crítico! " . $msg;
        }

        // Aplicar efecto de estado si tiene
        if ($resultado['efecto'] !== null) {
            $objetivo->aplicar_efecto($resultado['efecto'], 3);
            $msg .= "\n{$objetivo->get_nombre()} fue afectado por: {$resultado['efecto']}";
        }

        return $msg;
    }

    // --- Efectos de estado ---

    public function aplicar_efecto(string $efecto, int $turnos): void
    {
        $this->efectos_activos[$efecto] = $turnos;
    }

    public function procesar_efectos(): string
    {
        $log = '';
        foreach ($this->efectos_activos as $efecto => $turnos) {
            if ($efecto === 'quemadura') {
                $log .= $this->sub_vida(10.0) . "\n";
            }
            if ($efecto === 'veneno') {
                $log .= $this->sub_vida(5.0) . "\n";
            }
            $this->efectos_activos[$efecto]--;
            if ($this->efectos_activos[$efecto] <= 0) {
                unset($this->efectos_activos[$efecto]);
            }
        }
        return $log;
    }

    // --- Experiencia y nivel ---

    public function add_experiencia(float $cantidad): string
    {
        $this->experiencia += $cantidad;
        $msg = "{$this->nombre} ganó {$cantidad} de experiencia";

        if ($this->experiencia >= $this->exp_para_subir) {
            $msg .= "\n" . $this->subir_nivel();
        }

        return $msg;
    }

    private function subir_nivel(): string
    {
        $this->nivel++;
        $this->experiencia    -= $this->exp_para_subir;
        $this->exp_para_subir *= 1.5; // cada nivel cuesta más

        // Mejora de stats al subir nivel
        $this->vida_max    += 20.0;
        $this->vida         = $this->vida_max; // se cura al subir
        $this->mana        += 10.0;
        $this->resistencia += 0.5;

        return "{$this->nombre} subió al nivel {$this->nivel}";
    }

    // --- Inventario ---

    public function usar_pocion(): string
    {
        $cura = $this->inventario->usar_pocion();
        if ($cura === null) {
            return "{$this->nombre} no tiene pociones";
        }
        $this->add_vida($cura);
        return "{$this->nombre} usó una poción y recuperó {$cura} de vida";
    }

    // --- Getters ---

    public function get_nombre(): string          { return $this->nombre; }
    public function get_image(): string           { return $this->image; }
    public function get_vida(): float             { return $this->vida; }
    public function get_vida_max(): float         { return $this->vida_max; }
    public function get_mana(): float             { return $this->mana; }
    public function get_energia(): float          { return $this->energia; }
    public function get_nivel(): int              { return $this->nivel; }
    public function get_experiencia(): float      { return $this->experiencia; }
    public function get_habilidades(): array      { return $this->habilidades; }
    public function get_inventario(): Inventario  { return $this->inventario; }
    public function get_efectos(): array          { return $this->efectos_activos; }
}