<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use Root\Program\Mod\Data;

function response($data, $status = 200) 
{
    http_response_code($status);
    echo json_encode($data);
    exit;
}

function validate_game_data($game_data_json)
{     

    if (!is_array($game_data_json)) {
        response(["error" => "invalid JSON"], 400);
    }



    if 
    (
        !isset($game_data_json['canvas']) ||
        !is_array($game_data_json['canvas'])
    ) 
    {
        response(["error" => "invalid canvas"], 400);
    }



    $width = filter_var($game_data_json['canvas']['width'] ?? null, FILTER_VALIDATE_INT);
    $height = filter_var($game_data_json['canvas']['height'] ?? null, FILTER_VALIDATE_INT);


    if ($width === false || $height === false) 
    {
        response(["error" => "invalid canvas.height or canvas.height"], 400);
    }

    if ($width <= 0 || $height <= 0) 
    {
        response(["error" => "invalid canvas.width or canvas.height"], 400);
    }

}


// read raw bytes datas
$raw = file_get_contents("php://input");


new Data();

if ($raw === false || $raw === '') {
    response(["error" => "empty request"], 400);
}

$data = json_decode($raw, true);

validate_game_data($data);

$result = file_put_contents(
    __DIR__ . "/game_data.log",
    $raw . PHP_EOL,
    FILE_APPEND
);

if ($result === false) {
    response(["error" => "failed to save file"], 500);
}

response(["ok" => true]);
