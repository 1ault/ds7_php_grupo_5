<?php
declare(strict_types=1);

namespace Root\Program\Modelo;

use Root\Program\Config\Layer8;

use PDO;

class Servicio
{
    private readonly int  $id;
    public function __construct
    (
        private readonly string $nombre,
        private readonly string $precio,
    )
    {
    }


    public function insert(): string
    {
    }

    public function edit(): void
    {
    }

    public function remove(): void
    {
    }


    public function listar(): array
    {
    }

}
