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
            $conexion = new PDO(
                getenv('DB_DSN_WINDOWS'),
                getenv('DB_USUARIO'),
                getenv('DB_CONTRASENA')
            );
            
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
