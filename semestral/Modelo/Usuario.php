<?php
declare(strict_types=1);

namespace Root\Program\Modelo;

use Root\Program\Config\Layer8;
use Root\Program\Utils\CryptoVault;
use PDO;

class Usuario
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Layer8::get();
    }

    public function existeUsuario(string $usuario): bool
    {
        $consulta = $this->conexion->prepare(
            'SELECT id FROM usuarios WHERE indexing_usuario = :idx LIMIT 1'
        );
        $consulta->bindValue(':idx', CryptoVault::hashMessageAuthentication(data: $usuario));
        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function registrar(string $usuario, string $password): string
    {
        $consulta = $this->conexion->prepare(
            'INSERT INTO usuarios (usuario, password, indexing_usuario)
             VALUES (:usuario, :password, :idx)'
        );
        $consulta->bindValue(':usuario',  CryptoVault::securedEncrypt(data: $usuario));
        $consulta->bindValue(':password', CryptoVault::hashPassword(password: $password));
        $consulta->bindValue(':idx',      CryptoVault::hashMessageAuthentication(data: $usuario));
        $consulta->execute();
        return $this->conexion->lastInsertId();
    }

    public function obtenerUsuario(string $usuario, string $password): array
    {
        $consulta = $this->conexion->prepare(
            'SELECT id, usuario, password, rol, created_at
             FROM usuarios
             WHERE indexing_usuario = :idx
             LIMIT 1'
        );
        $consulta->bindValue(':idx', CryptoVault::hashMessageAuthentication(data: $usuario));
        $consulta->execute();
        $row = $consulta->fetch(PDO::FETCH_ASSOC);
        if (!$row) return [];
        if (!CryptoVault::verifyPassword(password: $password, hash: $row['password'])) return [];
        return [
            'id'         => $row['id'],
            'usuario'    => CryptoVault::securedDecrypt(data: $row['usuario']),
            'rol'        => $row['rol'],
            'created_at' => $row['created_at'],
        ];
    }
}
