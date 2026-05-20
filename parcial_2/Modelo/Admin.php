<?php
declare(strict_types=1);

namespace Root\Program\Modelo;

use Root\Program\Config\Database;
use Root\Program\Utils\CryptoVault;

use PDO;

class Admin
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Database::conectar();
    }

    public function obtenerTodos(): array
    {
        $consulta = $this->conexion->prepare(
            'SELECT
                a.id,
                a.usuario_id,
                a.cedula_pasaporte,
                a.nombre,
                a.apellido,
                a.genero,
                a.nacionalidad,
                a.telefono,
                a.correo,
                a.estado_solicitud,
                a.created_at
             FROM aspirantes a
             ORDER BY a.created_at DESC'
        );

        $consulta->execute();
        $filas = $consulta->fetchAll(PDO::FETCH_ASSOC);

        foreach ($filas as &$fila) {
            $fila['cedula_pasaporte'] = CryptoVault::securedDecrypt($fila['cedula_pasaporte']);
            $fila['nombre']           = CryptoVault::securedDecrypt($fila['nombre']);
            $fila['apellido']         = CryptoVault::securedDecrypt($fila['apellido']);
            $fila['nacionalidad']     = CryptoVault::securedDecrypt($fila['nacionalidad']);
            $fila['telefono']         = CryptoVault::securedDecrypt($fila['telefono']);
            $fila['correo']           = CryptoVault::securedDecrypt($fila['correo']);
        }
        unset($fila);

        return $filas;
    }

    public function actualizarEstado(int $usuario_id, string $estado_solicitud): bool
    {
        $consulta = $this->conexion->prepare(
            'UPDATE aspirantes
             SET estado_solicitud = :estado_solicitud
             WHERE usuario_id = :usuario_id'
        );

        $consulta->bindValue(':estado_solicitud', $estado_solicitud);
        $consulta->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $consulta->execute();

        // rowCount() = 0 si el valor ya era el mismo, igual se considera éxito
        return true;
    }
}
