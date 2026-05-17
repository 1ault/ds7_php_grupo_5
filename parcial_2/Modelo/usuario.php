<?php

require_once __DIR__ . "/../Config/cone.php";

class Usuario
{
    private $conexion;

    public function __construct()
    {
        $db = new Database();
        $this->conexion = $db->conectar();
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
        $sql = "INSERT INTO usuarios(usuario,password)
                VALUES(:usuario,:password)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":usuario" => htmlspecialchars($usuario),
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