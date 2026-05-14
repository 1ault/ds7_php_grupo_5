<?php
declare(strict_types=1);

namespace Root\Program\Config;

use PDO;

class Layer8
{
    public static function init()
    {
        
        // Crear conexion PDO
        $conexion = new PDO(
            DB_DSN,
            DB_USUARIO,
            DB_CONTRASENA
        );
        
        // Lanzar excepciones en errores
        $conexion->setAttribute(
            PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION
        );

        return $conexion;
    }
}

