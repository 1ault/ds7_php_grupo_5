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

    public static function listar(): array
    {
        $layer8 = Layer8::init();

        $consulta = $layer8->prepare(
            "SELECT * FROM servicio"
        );
        $consulta->execute();

        // Obtener resultados
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function pedir(array $pedidos): array
    {
        if (empty($pedidos)) {
            return [];
        }

        $layer8 = Layer8::init();

        $ids = array_map('intval', $pedidos);
        $ids = array_unique($ids);
        $ids = array_filter($ids, fn($id) => $id > 0);

        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql_query = "
            SELECT id, name, precio
            FROM servicio
            WHERE id 
            IN ($placeholders)
        ";

        $consulta = $layer8->prepare($sql_query);
        $consulta->execute($ids);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

}
