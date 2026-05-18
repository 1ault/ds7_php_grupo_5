<?php
declare(strict_types=1);

namespace Root\Program\Modelo;

use Root\Program\Config\Database;

class Usuario
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Database::conectar();
    }

    public function existeUsuario($usuario)
    {
        $sql = "SELECT id
                FROM usuarios
                WHERE usuario = :usuario";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute([
            ":usuario" => $usuario
        ]);

        return $stmt->fetch();
    }

    public function registrar($usuario, $password)
    {
        // Cifrar contraseña
        $passwordHash = password_hash(
            $password,
            PASSWORD_BCRYPT
        );

        // !TODO Cifrar usuario

        $sql = "INSERT INTO usuarios(usuario,password)
                VALUES(:usuario,:password)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":usuario" => $usuario,
            ":password" => $password
        ]);
    }

    public function obtenerUsuario($usuario)
    {
        $sql = "SELECT *
                FROM usuarios
                WHERE usuario = :usuario";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute([
            ":usuario" => $usuario
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
