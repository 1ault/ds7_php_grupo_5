<?php
declare(strict_types=1);

namespace Root\Program\Modelo;

use Root\Program\Config\Database;
use PDO;

class Preferencia
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Database::conectar();
    }

    /** IDs de géneros preferidos del usuario */
    public function obtenerPorUsuario(int $usuario_id): array
    {
        $consulta = $this->conexion->prepare(
            'SELECT genero_id FROM preferencias WHERE usuario_id = :uid'
        );
        $consulta->bindValue(':uid', $usuario_id, PDO::PARAM_INT);
        $consulta->execute();
        return array_column($consulta->fetchAll(PDO::FETCH_ASSOC), 'genero_id');
    }

    /** Reemplaza todas las preferencias del usuario */
    public function guardar(int $usuario_id, array $genero_ids): void
    {
        $del = $this->conexion->prepare('DELETE FROM preferencias WHERE usuario_id = :uid');
        $del->bindValue(':uid', $usuario_id, PDO::PARAM_INT);
        $del->execute();

        if (empty($genero_ids)) return;

        $ins = $this->conexion->prepare(
            'INSERT IGNORE INTO preferencias (usuario_id, genero_id) VALUES (:uid, :gid)'
        );
        foreach ($genero_ids as $gid) {
            $ins->bindValue(':uid', $usuario_id, PDO::PARAM_INT);
            $ins->bindValue(':gid', (int)$gid,   PDO::PARAM_INT);
            $ins->execute();
        }
    }
}
