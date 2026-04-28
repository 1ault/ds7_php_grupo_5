<?php
declare(strict_types=1);

namespace Root\Program\Routers;

enum HttpStatus: int {
    case OK = 200;
    case CREATED = 201;
    case BAD_REQUEST = 400;
    case NOT_FOUND = 404;
    case INTERNAL_SERVER_ERROR = 500;
}

class Api
{
    private string $uri;

    public function __construct()
    {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 
    }

    static public function response($data, $status = 200) 
    {
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    public function run()
    {
    }
    
}
