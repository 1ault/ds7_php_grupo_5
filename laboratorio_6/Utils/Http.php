<?php
declare(strict_types=1);

namespace Root\Program\Utils;

enum HttpStatus: int {
    case OK = 200;
    case CREATED = 201;
    case BAD_REQUEST = 400;
    case NOT_FOUND = 404;
    case INTERNAL_SERVER_ERROR = 500;
}

class Http
{
    static public function response(array $data, HttpStatus $status): void
    {
        http_response_code($status->value);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        exit;
    }
}
