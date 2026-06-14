<?php

namespace Root\Program\models;

use PDO;
use Exception;
use SoapClient;
use SoapFault;

class Pedidos
{
    private static function conexion(): PDO
    {
        $host = "localhost";
        $db = "veterinaria_patitas";
        $user = "root";
        $pass = "";

        return new PDO(
            "mysql:host=$host;dbname=$db;charset=utf8",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }

    private static function soap(): SoapClient
    {
        return new SoapClient(
            "http://localhost/Laboratorios/Laboratorio9/veterinaria_soap/inventario.wsdl"
        );
    }

    public static function factura(array $productos): array
    {
        try {
            $db = self::conexion();
            $soap = self::soap();

            $detalles = [];
            $total = 0;

            foreach ($productos as $item) {
                $producto_id = (int)$item["producto_id"];
                $cantidad = (int)$item["cantidad"];

                if ($cantidad <= 0) {
                    throw new Exception("La cantidad debe ser mayor a 0.");
                }

                $producto = $soap->obtenerProducto($producto_id);

                if ($producto->stock < $cantidad) {
                    throw new Exception("Stock insuficiente para: " . $producto->nombre);
                }

                $subtotal = $producto->precio * $cantidad;
                $total += $subtotal;

                $detalles[] = [
                    "producto_id" => $producto_id,
                    "nombre_producto" => $producto->nombre,
                    "precio" => $producto->precio,
                    "cantidad" => $cantidad,
                    "subtotal" => $subtotal
                ];
            }

            $db->beginTransaction();

            $stmtPedido = $db->prepare("
                INSERT INTO pedidos (total)
                VALUES (:total)
            ");

            $stmtPedido->execute([
                ":total" => $total
            ]);

            $pedido_id = (int)$db->lastInsertId();

            $stmtDetalle = $db->prepare("
                INSERT INTO detalle_pedido
                (pedido_id, producto_id, nombre_producto, precio, cantidad, subtotal)
                VALUES
                (:pedido_id, :producto_id, :nombre_producto, :precio, :cantidad, :subtotal)
            ");

            foreach ($detalles as $detalle) {
                $stmtDetalle->execute([
                    ":pedido_id" => $pedido_id,
                    ":producto_id" => $detalle["producto_id"],
                    ":nombre_producto" => $detalle["nombre_producto"],
                    ":precio" => $detalle["precio"],
                    ":cantidad" => $detalle["cantidad"],
                    ":subtotal" => $detalle["subtotal"]
                ]);

                // Restar stock en SOAP
                $soap->actualizarStock(
                    $detalle["producto_id"],
                    -$detalle["cantidad"]
                );
            }

            $db->commit();

            return [
                "success" => true,
                "mensaje" => "Pedido facturado correctamente.",
                "pedido_id" => $pedido_id,
                "total" => $total,
                "detalles" => $detalles
            ];

        } catch (SoapFault $e) {
            return [
                "success" => false,
                "mensaje" => "Error SOAP: " . $e->getMessage()
            ];

        } catch (Exception $e) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }

            return [
                "success" => false,
                "mensaje" => $e->getMessage()
            ];
        }
    }

    public static function obtenerUltimoPedido(): array
{
    $db = self::conexion();

    $stmt = $db->query("
        SELECT id, fecha, total
        FROM pedidos
        ORDER BY id DESC
        LIMIT 1
    ");

    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        return [];
    }

    $stmtDetalle = $db->prepare("
        SELECT producto_id, nombre_producto, precio, cantidad, subtotal
        FROM detalle_pedido
        WHERE pedido_id = :pedido_id
    ");

    $stmtDetalle->execute([
        ":pedido_id" => $pedido["id"]
    ]);

    $detalles = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);

    return [
        "pedido" => $pedido,
        "detalles" => $detalles
    ];
}
}
?>