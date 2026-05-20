<?php
declare(strict_types=1);

namespace Root\Program\Modelo;

use Root\Program\Config\Database;
use Root\Program\Utils\CryptoVault;

use PDO;

class Aspirante
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Database::conectar();
    }

    /**
     * Inserta una nueva solicitud de aspirante.
     */
    public function guardar(array $datos): string
    {
        $consulta = $this->conexion->prepare(
            'INSERT INTO aspirantes
            (
                usuario_id, cedula_pasaporte, nombre, apellido,
                estado_civil, genero, tipo_sangre, fecha_nacimiento,
                nacionalidad, telefono, residencia, correo, estado_solicitud
            )
            VALUES
            (
                :usuario_id, :cedula_pasaporte, :nombre, :apellido,
                :estado_civil, :genero, :tipo_sangre, :fecha_nacimiento,
                :nacionalidad, :telefono, :residencia, :correo, :estado_solicitud
            )'
        );

        $consulta->bindValue(':usuario_id',       $datos['usuario_id'],   PDO::PARAM_INT);
        $consulta->bindValue(':cedula_pasaporte', CryptoVault::securedEncrypt(data: $datos['cedula']));
        $consulta->bindValue(':nombre',           CryptoVault::securedEncrypt(data: $datos['nombre']));
        $consulta->bindValue(':apellido',         CryptoVault::securedEncrypt(data: $datos['apellido']));
        $consulta->bindValue(':estado_civil',     $datos['estado_civil']);
        $consulta->bindValue(':genero',           $datos['genero']);
        $consulta->bindValue(':tipo_sangre',      $datos['tipo_sangre']);
        $consulta->bindValue(':fecha_nacimiento', $datos['fecha_nacimiento']);
        $consulta->bindValue(':nacionalidad',     CryptoVault::securedEncrypt(data: $datos['nacionalidad']));
        $consulta->bindValue(':telefono',         CryptoVault::securedEncrypt(data: $datos['telefono']));
        $consulta->bindValue(':residencia',       CryptoVault::securedEncrypt(data: $datos['residencia']));
        $consulta->bindValue(':correo',           CryptoVault::securedEncrypt(data: $datos['correo']));
        $consulta->bindValue(':estado_solicitud', 'no revisado');

        $consulta->execute();

        return $this->conexion->lastInsertId();
    }

    /**
     * Obtiene la solicitud de un aspirante por usuario_id.
     * Devuelve null si no existe ninguna.
     */
    public function obtenerPorUsuario(int $usuario_id): ?array
    {
        $consulta = $this->conexion->prepare(
            'SELECT *
             FROM aspirantes
             WHERE usuario_id = :usuario_id
             LIMIT 1'
        );

        $consulta->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $consulta->execute();

        $fila = $consulta->fetch(PDO::FETCH_ASSOC);
        if (!$fila) return null;

        // Descifrar campos sensibles para mostrar al aspirante
        $fila['cedula_pasaporte'] = CryptoVault::securedDecrypt(data: $fila['cedula_pasaporte']);
        $fila['nombre']           = CryptoVault::securedDecrypt(data: $fila['nombre']);
        $fila['apellido']         = CryptoVault::securedDecrypt(data: $fila['apellido']);
        $fila['nacionalidad']     = CryptoVault::securedDecrypt(data: $fila['nacionalidad']);
        $fila['telefono']         = CryptoVault::securedDecrypt(data: $fila['telefono']);
        $fila['residencia']       = CryptoVault::securedDecrypt(data: $fila['residencia']);
        $fila['correo']           = CryptoVault::securedDecrypt(data: $fila['correo']);

        return $fila;
    }

    /**
     * Actualiza los datos de la solicitud de un aspirante (solo si está 'no revisado').
     */
    public function actualizar(int $usuario_id, array $datos): bool
    {
        $consulta = $this->conexion->prepare(
            'UPDATE aspirantes
             SET
                cedula_pasaporte  = :cedula_pasaporte,
                nombre            = :nombre,
                apellido          = :apellido,
                estado_civil      = :estado_civil,
                genero            = :genero,
                tipo_sangre       = :tipo_sangre,
                fecha_nacimiento  = :fecha_nacimiento,
                nacionalidad      = :nacionalidad,
                telefono          = :telefono,
                residencia        = :residencia,
                correo            = :correo
             WHERE usuario_id     = :usuario_id
               AND estado_solicitud = \'no revisado\''
        );

        $consulta->bindValue(':usuario_id',       $usuario_id,  PDO::PARAM_INT);
        $consulta->bindValue(':cedula_pasaporte', CryptoVault::securedEncrypt(data: $datos['cedula']));
        $consulta->bindValue(':nombre',           CryptoVault::securedEncrypt(data: $datos['nombre']));
        $consulta->bindValue(':apellido',         CryptoVault::securedEncrypt(data: $datos['apellido']));
        $consulta->bindValue(':estado_civil',     $datos['estado_civil']);
        $consulta->bindValue(':genero',           $datos['genero']);
        $consulta->bindValue(':tipo_sangre',      $datos['tipo_sangre']);
        $consulta->bindValue(':fecha_nacimiento', $datos['fecha_nacimiento']);
        $consulta->bindValue(':nacionalidad',     CryptoVault::securedEncrypt(data: $datos['nacionalidad']));
        $consulta->bindValue(':telefono',         CryptoVault::securedEncrypt(data: $datos['telefono']));
        $consulta->bindValue(':residencia',       CryptoVault::securedEncrypt(data: $datos['residencia']));
        $consulta->bindValue(':correo',           CryptoVault::securedEncrypt(data: $datos['correo']));

        $consulta->execute();

        return $consulta->rowCount() > 0;
    }
}
