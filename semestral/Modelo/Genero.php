<?php
declare(strict_types=1);

namespace Root\Program\Modelo;

use Root\Program\Config\Database;
use PDO;

class Genero
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Database::conectar();
    }

    public function obtenerTodos(): array
    {
        $consulta = $this->conexion->prepare('SELECT id, nombre FROM generos ORDER BY nombre ASC');
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
