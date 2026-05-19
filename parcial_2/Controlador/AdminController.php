<?php
declare(strict_types=1);

namespace Root\Program\Controlador;

use Root\Program\Utils\Http;
use Root\Program\Utils\HttpStatus;

class AdminController
{
    public static function vistaAdmin(): void
    {
        if (empty($_SESSION['usuario_id'])) 
        {
            $_SESSION['user_logs'] = ["Usuario no logueado."];
            header("Location: /login");
            exit;
        }

        // rol ENUM('aspirante','rh') DEFAULT 'aspirante' NOT NULL,

        require_once __DIR__ . "/../Vista/Admin.php";
    }

    public static function postAdminUpdateAspirante(): void
    {
        if (empty($_SESSION['usuario_id'])) 
        {
            $_SESSION['user_logs'] = ["Usuario no logueado."];
            header("Location: /login");
            exit;
        }

        // rol ENUM('aspirante','rh') DEFAULT 'aspirante' NOT NULL,
    }

    public static function apiAdminUpdateAspirante(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER["REQUEST_METHOD"] !== "POST") 
        {
            Http::response(
                ['err' => 'only post'],
                HttpStatus::NOT_FOUND 
            );
        }

        if (empty($_SESSION['usuario_id'])) 
        {
            Http::response(
                ['err' => 'usuario no logueado'],
                HttpStatus::NOT_FOUND 
            );
        }

        // rol ENUM('aspirante','rh') DEFAULT 'aspirante' NOT NULL,

        $result = self::logicAdminUpdateAspirante($_POST);

        Http::response(
            ['ok' => 'aspirante update'],
            HttpStatus::OK
        );
    }

    public static function logicAdminUpdateAspirante(array: $data): array
    {
        $data['usuario_id'];
        $data['estado_solicitud'];

        // rol ENUM('aspirante','rh') DEFAULT 'aspirante' NOT NULL,
    }
}
