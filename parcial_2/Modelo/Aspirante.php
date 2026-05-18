<?php
declare(strict_types=1);

namespace Root\Program\Modelo;

use Root\Program\Config\Database;

class Aspirante
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Database::conectar();
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

        $consulta =
            $this->conexion->prepare($sql);

        return $consulta->execute([
            ":usuario_id" =>$datos["usuario_id"],
            ":cedula_pasaporte" =>htmlspecialchars($datos["cedula"]),
            ":nombre" =>htmlspecialchars($datos["nombre"]),
            ":apellido" =>htmlspecialchars($datos["apellido"]),
            ":estado_civil" =>htmlspecialchars($datos["estado_civil"]),
            ":genero" =>htmlspecialchars($datos["genero"]),
            ":tipo_sangre" =>htmlspecialchars($datos["tipo_sangre"]),
            ":fecha_nacimiento" =>$datos["fecha_nacimiento"],
            ":nacionalidad" =>htmlspecialchars($datos["nacionalidad"]),
            ":telefono" =>htmlspecialchars($datos["telefono"]),
            ":residencia" =>htmlspecialchars($datos["residencia"]),
            ":correo" =>htmlspecialchars($datos["correo"])
        ]);
    }
}
