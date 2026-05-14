<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use Root\Program\Game;

// header('Content-Type: application/json');

$raw = file_get_contents("php://input");

Game::getPersonajeKangre();

if ($raw === false || $raw === '') {
    Game::response(["error" => "empty request"], 400);
}

$data = json_decode($raw, true);

if (!isset($data['name'])) {
    Game::response(["error" => "data.name not set"], 400);
}

if (!is_string($data['name'])) {
    Game::response(["error" => "data.name not string"], 400);
}

$name = trim($data['name']);

if ($name === '') {
    Game::response(["error" => "data.name empty"], 400);
}

if (strlen($name) >= 50) {
    Game::response(["error" => "data.name to long"], 400);
}


// HashMap, map, associate array, object
$handlers_specification = [
    'kangre'  => fn() => Game::getPersonajeKangre(),
    'champi'  => fn() => Game::getFondoChampi(),
    'jojo' => fn() => Game::getFondoJojo(),
];


if (!isset($handlers_specification[$name])) {
    Game::response(["error" => "invalid specification"], 400);
}

$handlers_specification[$name];
