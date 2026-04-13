<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use Root\Program\Game;

// header("Content-Type: application/json; charset=utf-8");

$raw = file_get_contents("php://input");

Game::loadGameState();
