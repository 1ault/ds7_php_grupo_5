<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use Root\Program\Game;
use Root\Program\HttpStatus;

// header("Content-Type: application/json; charset=utf-8");

$raw = file_get_contents("php://input");

// Game::signalGameStateHabilidad();

if ($raw === false || $raw === '') {
    Game::response
    (
        ["error" => "input not correct"],
        HttpStatus::INTERNAL_SERVER_ERROR->value
    );
}

$data = json_decode($raw, true);
if (!is_array($data)) {
    Game::response
    (
        ["error" => "is not array"],
        HttpStatus::INTERNAL_SERVER_ERROR->value
    );
}


match ($data['signal']) {
    'atacar' => Game::signalGameStateAtacar(),
    'habilidad' => Game::signalGameStateHabilidad(),
    default => Game::response
        (
            ["error" => "not load storage"], 
            HttpStatus::INTERNAL_SERVER_ERROR->value
        ),
};

