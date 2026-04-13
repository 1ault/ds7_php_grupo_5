<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use Root\Program\Game;

// header("Content-Type: application/json; charset=utf-8");
//
$raw = file_get_contents("php://input");


Game::signalAtaqueGameState();


if ($raw === false || $raw === '') {
    exit;
}

$data = json_decode($raw, true);


if (!is_array($game_data_json)) {
    exit;
}

if ($data['signal'] === 'ataque')
{

    Game::signalAtaqueGameState();
} 


Game::response(["error" => "not load storage"], HttpStatus::NOT_FOUND);
