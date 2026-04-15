<?php
declare(strict_types=1);
require_once __DIR__ . '/../../vendor/autoload.php';

use Root\Program\Game;
use Root\Program\Mod\Personaje\Hongo;
use Root\Program\Mod\Personaje\Cangrejo;
use Root\Program\Mod\Personaje\Axolote;

session_start();

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);
$ronda = $data['ronda'] ?? 1;

$nombrePersonaje = $data['personaje'] ?? null;
$items           = $data['items']     ?? [];

if (!$nombrePersonaje) {
    Game::response(['error' => 'personaje no especificado'], 400);
}

// Crear objeto jugador
$objJugador = match($nombrePersonaje) {
    'Hongo'    => new Hongo(),
    'Cangrejo' => new Cangrejo(),
    'Axolote'  => new Axolote(),
    default    => null
};

if (!$objJugador) {
    Game::response(['error' => 'personaje inválido'], 400);
}

// Enemigo aleatorio distinto al jugador
$opciones = array_filter(
    ['Hongo', 'Cangrejo', 'Axolote'],
    fn($n) => $n !== $nombrePersonaje
);
$nombreEnemigo = $opciones[array_rand($opciones)];

$objEnemigo = match($nombreEnemigo) {
    'Hongo'    => new Hongo(),
    'Cangrejo' => new Cangrejo(),
    'Axolote'  => new Axolote(),
};

$vidaExtra   = ($ronda - 1) * 20;
$ataqueExtra = ($ronda - 1) * 5;

// Calcular bonuses de ítems equipados
$bonusAtaque  = 0;
$bonusDefensa = 0;
$pociones     = 0;

foreach ($items as $item) {
    match($item['tipo'] ?? '') {
        'arma'    => $bonusAtaque  += (int)($item['efecto'] ?? 0),
        'defensa' => $bonusDefensa += (int)($item['efecto'] ?? 0),
        'pocion'  => $pociones++,
        default   => null,
    };
}

// Construir estado inicial del combate
$_SESSION['combate'] = [
    'jugador' => [
        'nombre'       => $objJugador->get_nombre(),
        'vida'         => $objJugador->get_vida(),
        'vida_max'     => $objJugador->get_vida(),
        'mana'         => $objJugador->get_mana(),
        'mana_max'     => $objJugador->get_mana(),
        'habilidades'  => array_map(
            fn($h) => $h->get_nombre(),
            $objJugador->get_habilidades()
        ),
        'bonus_ataque'  => $bonusAtaque,
        'bonus_defensa' => $bonusDefensa,
        'pociones'      => $pociones,
    ],
    'enemigo' => [
        'nombre'       => $objEnemigo->get_nombre(),
    'vida'         => $objEnemigo->get_vida() + $vidaExtra,
    'vida_max'     => $objEnemigo->get_vida() + $vidaExtra,
    'mana'         => $objEnemigo->get_mana(),
    'mana_max'     => $objEnemigo->get_mana(),
    'habilidades'  => array_map(
        fn($h) => $h->get_nombre(),
        $objEnemigo->get_habilidades()
    ),
    'bonus_ataque'  => $ataqueExtra,
    'bonus_defensa' => 0,
    'pociones'      => 0,
    ],
];

Game::response($_SESSION['combate']);