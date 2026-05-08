<?php
declare(strict_types=1);

namespace Root\Program\Modelo;

use Root\Program\Config\Layer8;

class Usuario
{

    public function __construct
    (
        int $id = -1,
        string $nombre = '',
        string $contrasena = '',
    )
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->contrasena = $contrasena;
    }

    public function login()
    {
        $layer8 = Layer8::Init();

        $consulta = $layer8->prepare(
            "SELECT * FROM account
              WHERE username = 'user'
              AND   sha_pass_hash = 'user'
            "
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }


    public function registro()
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
