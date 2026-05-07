<?php
declare(strict_types=1);
require_once __DIR__ . '/../../vendor/autoload.php';

use Root\Program\Game;
use Root\Program\Mod\Personaje\Hongo;
use Root\Program\Mod\Personaje\Cangrejo;
use Root\Program\Mod\Personaje\Axolote;

session_start();

if (!isset($_SESSION['combate'])) {
    Game::response(['error' => 'no hay combate activo'], 400);
}

$raw    = file_get_contents('php://input');
$data   = json_decode($raw, true);
$accion = $data['accion'] ?? null; 

if (!$accion) {
    Game::response(['error' => 'acción inválida'], 400);
}

$estado = &$_SESSION['combate'];
$jugador = &$estado['jugador'];
$enemigo = &$estado['enemigo'];
$log = [];

// Recrear objetos PHP para usar sus métodos
$objJugador = match($jugador['nombre']) {
    'Hongo'    => new Hongo(),
    'Cangrejo' => new Cangrejo(),
    'Axolote'  => new Axolote(),
};
$objEnemigo = match($enemigo['nombre']) {
    'Hongo'    => new Hongo(),
    'Cangrejo' => new Cangrejo(),
    'Axolote'  => new Axolote(),
};

if ($accion === 'pocion_vida' || $accion === 'pocion_mana') {
    if ($accion === 'pocion_vida') {
    if ($jugador['pociones'] <= 0) {
        Game::response(['error' => 'no tienes pociones'], 400);
    }

    $jugador['pociones']--;
    $vidaAntes = $jugador['vida'];

$cura = 50;
$jugador['vida'] = min($jugador['vida_max'], $jugador['vida'] + $cura);

$curadoReal = $jugador['vida'] - $vidaAntes;

$log[] = "❤️ {$jugador['nombre']} recuperó {$curadoReal} de vida";
}

if ($accion === 'pocion_mana') {
    $manaAntes = $jugador['mana'];

$curaMana = 50;
$jugador['mana'] = min($jugador['mana_max'], $jugador['mana'] + $curaMana);

$manaRecuperado = $jugador['mana'] - $manaAntes;

$log[] = "💧 {$jugador['nombre']} recuperó {$manaRecuperado} de mana";
}

} else {
    // Obtener habilidad
    $habilidades = $objJugador->get_habilidades();
    $habilidad   = $accion === 'normal' ? $habilidades[0] : $habilidades[1];

    if ($jugador['mana'] < $habilidad->get_coste()) {
        Game::response(['error' => 'mana insuficiente'], 400);
    }

    // Consumir mana
    $jugador['mana'] -= $habilidad->get_coste();

    // Calcular daño
    $resultado = $habilidad->calcular_dano();
    $dano = $resultado['dano'] + $jugador['bonus_ataque'];

    // Aplicar daño al enemigo
    $enemigo['vida'] = max(0.0, $enemigo['vida'] - $dano);

    $msg = "{$jugador['nombre']} usó {$habilidad->get_nombre()} — {$enemigo['nombre']} recibió {$dano} de daño. Vida: {$enemigo['vida']}";
    if ($resultado['es_critico']) $msg = "💥 ¡Crítico! " . $msg;
    $log[] = $msg;
}

// Verificar si enemigo murió
if ($enemigo['vida'] <= 0) {
    $log[] = "🏆 ¡{$enemigo['nombre']} fue derrotado!";
    Game::response([
        'jugador' => $jugador,
        'enemigo' => $enemigo,
        'log'     => $log,
        'fin'     => true,
        'gano'    => true,
    ]);
}

// --- TURNO ENEMIGO (automático) ---
$habilidadesEnemigo = $objEnemigo->get_habilidades();
$usaEspecial = $enemigo['mana'] >= $habilidadesEnemigo[1]->get_coste() && mt_rand(0, 100) < 40;
$habilidadEnemigo = $usaEspecial ? $habilidadesEnemigo[1] : $habilidadesEnemigo[0];

if ($enemigo['mana'] >= $habilidadEnemigo->get_coste()) {
    $enemigo['mana'] -= $habilidadEnemigo->get_coste();
    $resultadoEnemigo = $habilidadEnemigo->calcular_dano();
    $danoEnemigo = $resultadoEnemigo['dano'];

// 🛡️ aplicar defensa del jugador
$danoReducido = max(0, $danoEnemigo - $jugador['bonus_defensa']);

$jugador['vida'] = max(0.0, $jugador['vida'] - $danoReducido);

// 🔥 log mejorado
$msg = "{$enemigo['nombre']} usó {$habilidadEnemigo->get_nombre()} — {$jugador['nombre']} recibió {$danoReducido} de daño";

if ($jugador['bonus_defensa'] > 0) {
    $msg .= " (🛡️ -{$jugador['bonus_defensa']} defensa)";
}

$msg .= ". Vida: {$jugador['vida']}";

if ($resultadoEnemigo['es_critico']) {
    $msg = "💥 ¡Crítico! " . $msg;
}

$log[] = $msg;

    $msg = "{$enemigo['nombre']} usó {$habilidadEnemigo->get_nombre()} — {$jugador['nombre']} recibió {$danoEnemigo} de daño. Vida: {$jugador['vida']}";
    if ($resultadoEnemigo['es_critico']) $msg = "💥 ¡Crítico! " . $msg;
    $log[] = $msg;
}

// Verificar si jugador murió
$fin   = $jugador['vida'] <= 0;
$gano  = null;
if ($fin) {
    $log[]  = "💀 ¡{$jugador['nombre']} fue derrotado!";
    $gano   = false;
}

// Guardar estado actualizado en sesión
$_SESSION['combate'] = $estado;

Game::response([
    'jugador' => $jugador,
    'enemigo' => $enemigo,
    'log'     => $log,
    'fin'     => $fin,
    'gano'    => $gano,
]);