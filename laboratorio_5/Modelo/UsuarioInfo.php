<?php
declare(strict_types=1);

namespace Root\Program\Modelo;

use Root\Program\Config\Layer8;

class UsuarioInfo
{
    private int $id;
    private string $fecha_nacimiento;
    private string $genero;
    private string $nacionalidad;
    private string $residencia;
    private string $telefono;
    private string $correo;

    public function __construct
    (
    )
    {
    }


    public function insert()
    {
        $layer8 = Layer8::Init();
        
        // Prepare la operacion INSERT
        $consulta = $layer8->prepare(
            "INSERT INTO libro (nombre, contrasena)
                VALUES (:nombre, :contrasena) 
            "
        );

        // VIncular las parametros
        $consulta->bindParam(':nombre', $this->nombre);
        $consulta->bindParam(':contrasena', $this->contrasena);
        
        // Ejecutar
        $consulta->execute();

        // Obtener resultados
        return $layer8->lastInsertId();
    }

}
