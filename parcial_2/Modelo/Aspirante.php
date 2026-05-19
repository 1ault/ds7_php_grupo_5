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


    public function guardar(array $datos): string
    {
        // Prepare la operacion INSERT
        $consulta = $this->conexion->prepare(
            'INSERT INTO aspirantes
            (
                usuario_id,
                cedula_pasaporte,
                nombre,
                apellido,
                estado_civil,
                genero,
                tipo_sangre,
                fecha_nacimiento,
                nacionalidad,
                telefono,
                residencia,
                correo,
                estado_solicitud
            )
            VALUES
            (
                :usuario_id,
                :cedula_pasaporte,
                :nombre,
                :apellido,
                :estado_civil,
                :genero,
                :tipo_sangre,
                :fecha_nacimiento,
                :nacionalidad,
                :telefono,
                :residencia,
                :correo,
                :estado_solicitud
            )'
        );

        // Crear variables
        $crypto_cedula_pasaporte = CryptoVault::securedEncrypt(data: $datos['cedula']);
        $crypto_nombre = CryptoVault::securedEncrypt(data: $datos['nombre']);
        $crypto_apellido = CryptoVault::securedEncrypt(data: $datos['apellido']);
        $crypto_nacionalidad = CryptoVault::securedEncrypt(data: $datos['nacionalidad']);
        $crypto_telefono = CryptoVault::securedEncrypt(data: $datos['telefono']);
        $crypto_residencia = CryptoVault::securedEncrypt(data: $datos['residencia']);
        $crypto_correo = CryptoVault::securedEncrypt(data: $datos['correo']);

        // Vincular las parametros
        $consulta->bindValue(':usuario_id', $datos['usuario_id']);
        $consulta->bindValue(':cedula_pasaporte', $crypto_cedula_pasaporte);
        $consulta->bindValue(':nombre', $crypto_nombre);
        $consulta->bindValue(':apellido', $crypto_apellido);
        $consulta->bindValue(':estado_civil', $datos['estado_civil']);
        $consulta->bindValue(':genero', $datos['genero']);
        $consulta->bindValue(':tipo_sangre', $datos['tipo_sangre']);
        $consulta->bindValue(':fecha_nacimiento', $datos['fecha_nacimiento']);
        $consulta->bindValue(':nacionalidad', $crypto_nacionalidad);
        $consulta->bindValue(':telefono', $crypto_telefono);
        $consulta->bindValue(':residencia', $crypto_residencia);
        $consulta->bindValue(':correo',  $crypto_correo);
        $consulta->bindValue(':estado_solicitud', 'no revisado');
 
        // Ejecutar
        $consulta->execute();

        // Obtener resultados
        return $this->conexion->lastInsertId();
    }
} 
