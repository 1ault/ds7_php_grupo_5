<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use Root\Program\Game;

// header('Content-Type: application/json');

$raw = file_get_contents("php://input");

Game::getEnemigoChampi();
