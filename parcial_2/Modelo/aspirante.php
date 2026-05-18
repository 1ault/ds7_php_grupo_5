<?php

require_once __DIR__ . "/../Config/cone.php";

class Aspirante
{
    private PDO $conexion;

    public function __construct()
    {
        $database = new Database();
        $this->conexion = $database->conectar();
    }

    public function obtenerPorUsuarioId(int $usuario_id)
    {
        $sql = "
            SELECT *
            FROM aspirantes
            WHERE usuario_id = :usuario_id
            LIMIT 1
        ";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            ":usuario_id" => $usuario_id
        ]);

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function guardar(array $datos): bool
    {
        $sql = "
            INSERT INTO aspirantes
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
                'No Revisado'
            )
        ";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            ":usuario_id" => $datos["usuario_id"],
            ":cedula_pasaporte" => htmlspecialchars($datos["cedula"]),
            ":nombre" => htmlspecialchars($datos["nombre"]),
            ":apellido" => htmlspecialchars($datos["apellido"]),
            ":estado_civil" => htmlspecialchars($datos["estado_civil"]),
            ":genero" => htmlspecialchars($datos["genero"]),
            ":tipo_sangre" => htmlspecialchars($datos["tipo_sangre"]),
            ":fecha_nacimiento" => $datos["fecha_nacimiento"],
            ":nacionalidad" => htmlspecialchars($datos["nacionalidad"]),
            ":telefono" => htmlspecialchars($datos["telefono"]),
            ":residencia" => htmlspecialchars($datos["residencia"]),
            ":correo" => htmlspecialchars($datos["correo"])
        ]);
    }

    public function actualizar(array $datos): bool
    {
        $sql = "
            UPDATE aspirantes
            SET
                cedula_pasaporte = :cedula_pasaporte,
                nombre = :nombre,
                apellido = :apellido,
                estado_civil = :estado_civil,
                genero = :genero,
                tipo_sangre = :tipo_sangre,
                fecha_nacimiento = :fecha_nacimiento,
                nacionalidad = :nacionalidad,
                telefono = :telefono,
                residencia = :residencia,
                correo = :correo
            WHERE usuario_id = :usuario_id
        ";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            ":usuario_id" => $datos["usuario_id"],
            ":cedula_pasaporte" => htmlspecialchars($datos["cedula"]),
            ":nombre" => htmlspecialchars($datos["nombre"]),
            ":apellido" => htmlspecialchars($datos["apellido"]),
            ":estado_civil" => htmlspecialchars($datos["estado_civil"]),
            ":genero" => htmlspecialchars($datos["genero"]),
            ":tipo_sangre" => htmlspecialchars($datos["tipo_sangre"]),
            ":fecha_nacimiento" => $datos["fecha_nacimiento"],
            ":nacionalidad" => htmlspecialchars($datos["nacionalidad"]),
            ":telefono" => htmlspecialchars($datos["telefono"]),
            ":residencia" => htmlspecialchars($datos["residencia"]),
            ":correo" => htmlspecialchars($datos["correo"])
        ]);
    }
}