<?php
declare(strict_types=1);

namespace Root\Program\Modelo;

use Root\Program\Config\Layer8;

use PDO;

class Servicio
{
    
    private int $id;
    private string $nombre;
    private string $autor;
    private string $fecha;
    private string $categoria;
    private string $img;

    public function __construct
    (
        int $id = -1,
        string $nombre = '',
        string $autor = '',
    )
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->autor = $autor;
        $this->fecha = $fecha;
        $this->categoria = $categoria;
        $this->img = $img;
    }


    public function insert(): string
    {
        $layer8 = Layer8::Init();
        
        // Prepare la operacion INSERT
        $consulta = $layer8->prepare(
            "INSERT INTO libro (nombre, autor, fecha, categoria, img)
                VALUES (:nombre, :autor, :fecha, :categoria, :img) 
            "
        );

        // VIncular las parametros
        $consulta->bindParam(':nombre', $this->nombre);
        $consulta->bindParam(':autor', $this->autor);
        $consulta->bindParam(':fecha', $this->fecha);
        $consulta->bindParam(':categoria', $this->categoria);
        $consulta->bindParam(':img', $this->img);
        
        // Ejecutar
        $consulta->execute();

        // Obtener resultados
        return $layer8->lastInsertId();
    }

    public function edit(): void
    {

        $layer8 = Layer8::Init();

        $consulta = $layer8->prepare(
            "UPDATE libro 
                SET
                    nombre = :nombre,
                    autor = :autor,
                    fecha = :fecha,
                    categoria = :categoria,
                    img = :img
                WHERE id = :id
            "
        );

        // vincular las parametros
        $consulta->bindParam(':nombre', $this->nombre);
        $consulta->bindParam(':autor', $this->autor);
        $consulta->bindParam(':fecha', $this->fecha);
        $consulta->bindParam(':categoria', $this->categoria);
        $consulta->bindParam(':img', $this->img);
        $consulta->bindParam(':id', $this->id);

        $consulta->execute();
    }

    public function remove(): void
    {

        $layer8 = Layer8::Init();

        $consulta = $layer8->prepare(
            "DELETE FROM libro 
            WHERE id = :id"
        );

        // vincular las parametros
        $consulta->bindParam(':id', $this->id);

        $consulta->execute();

        $afectados = $consulta->rowCount();
    }


    public function listar(): array
    {
        $layer8 = Layer8::init();

        $consulta = $layer8->prepare(
            "SELECT * FROM libro"
        );
        $consulta->execute();

        // Obtener resultados
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

}
