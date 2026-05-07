<?php
declare(strict_types=1);

namespace Root\Program;

use Root\Program\Mod\Fondo;

enum HttpStatus: int {
    case OK = 200;
    case CREATED = 201;
    case BAD_REQUEST = 400;
    case NOT_FOUND = 404;
    case INTERNAL_SERVER_ERROR = 500;
}

class Game
{

    static public function response($data, $status = 200) 
    {
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    static public function getFondoMain(): Fondo
    {
        http_response_code(HttpStatus::OK->value);
        $fondo = new Fondo('main', 'assets/img/fondo.webp');
        echo json_encode($fondo);
        exit; 
    }

}
