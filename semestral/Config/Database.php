<?php
declare(strict_types=1);

namespace Root\Program\Config;

use PDO;

class Database
{

    public static function conectar(): PDO
    {
        try {
        // Crear conexion PDO

            switch (PHP_OS_FAMILY) 
            {
                case 'Windows':
                    $conexion = new PDO(
                        getenv('DB_WINDOWS_DNS'),
                        getenv('DB_WINDOWS_USERNAME'),
                        getenv('DB_WINDOWS_PASSWORD')
                    );
                    break;
                case 'BSD':
                    $conexion = new PDO(
                        getenv('DB_BSD_DNS'),
                        getenv('DB_BSD_USERNAME'),
                        getenv('DB_BSD_PASSWORD')
                    );
                    break;
                default:
                    exit("Unknown operating system family: " . PHP_OS_FAMILY);
                break;
            }
 
            // Lanzar excepciones en errores
            $conexion->setAttribute(
                PDO::ATTR_ERRMODE, 
                PDO::ERRMODE_EXCEPTION
            );

            return $conexion;

        } catch(PDOException $e) {
            die("Error de conexión.");
        }
    }

}
