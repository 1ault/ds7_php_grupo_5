<?php
declare(strict_types=1);

namespace Root\Program\Modelo;

use Root\Program\Config\Database;
use Root\Program\Utils\CryptoVault;

use PDO;

class Usuario
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Database::conectar();
    }

    public function existeUsuario(string $usuario): bool
    {
        // Prepare to SELECT
        $consulta = $this->conexion->prepare(
            'SELECT id 
             FROM usuarios 
             WHERE indexing_usuario = :indexing_usuario'
        );

        // Create hash to search
        $indexing_usuario = CryptoVault::hashMessageAuthentication(data: $usuario);

        // Bind
        $consulta->bindValue(':indexing_usuario', $indexing_usuario); // ✅ fixed name

        // Ejecutar
        $consulta->execute();

        // Return true if user exists, false if not
        return $consulta->fetch(PDO::FETCH_ASSOC) !== false; // ✅ returns bool
    }


    public function registrar($usuario, $password): string
    {

        // Prepare la operacion INSERT
        $consulta = $this->conexion->prepare(
            'INSERT INTO usuarios
            (
                usuario,
                password,
                indexing_usuario
            )
            VALUES 
            (
                :usuario, 
                :password,
                :indexing_usuario
            )'
        );

        // Crear variables
        $crypto_usuario = CryptoVault::securedEncrypt(data: $usuario);
        $crypto_password = CryptoVault::hashPassword(password: $password);
        $indexing_usuario = CryptoVault::hashMessageAuthentication(data: $usuario);

        // Vincular las parametros
        $consulta->bindValue(':usuario', $crypto_usuario);
        $consulta->bindValue(':password', $crypto_password);
        $consulta->bindValue(':indexing_usuario', $indexing_usuario);

        // Ejecutar
        $consulta->execute();
 
        // Obtener resultados
        return $this->conexion->lastInsertId();
    }

    public function obtenerUsuario(string $usuario, string $password): array
    {
        
        // Prepare la operacion INSERT
         $consulta = $this->conexion->prepare(
            'SELECT 
                id, 
                usuario, 
                password, 
                rol, 
                created_at
             FROM usuarios
             WHERE indexing_usuario = :indexing_usuario
             LIMIT 1'
        );

        // Vincular las parametros
        $indexing_usuario = CryptoVault::hashMessageAuthentication(data: $usuario);
        $consulta->bindValue(':indexing_usuario', $indexing_usuario);
        // Ejecutar
        $consulta->execute();
            
        // Obtener usuario
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
        if (!$usuario) {
            return [];
        }
        // verificar password
        $verify_password = CryptoVault::verifyPassword(
            password: $password,
            hash: $usuario['password'] 
        );
        if (!$verify_password) {
            return [];
        }
        return [
            'id' => $usuario['id'],
            'usuario' => CryptoVault::securedDecrypt(data: $usuario['usuario']),
            'rol'=> $usuario['rol'],
            'created_at' => $usuario['created_at']
        ];   
    }
}
