<?php
declare(strict_types=1);

namespace Root\Program\controllers;

use Root\Program\models\Pedidos;


class PedidosController
{
    public static function vistatest(): void
    {
        require_once __DIR__ . "/../../cliente_web/test.php";
        exit;
    }

 public static function facturar(): void
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        echo "Método no permitido";
        exit;
    }

    $productos = [];

    foreach ($_POST["producto_id"] as $i => $producto_id) {
        if (!empty($producto_id) && !empty($_POST["cantidad"][$i])) {
            $productos[] = [
                "producto_id" => (int)$producto_id,
                "cantidad" => (int)$_POST["cantidad"][$i]
            ];
        }
    }

      Pedidos::factura($productos);

      header("Location: /resumen");
      exit;

}

    public static function resumen(): void
    {
        
        $data = Pedidos::obtenerUltimoPedido();
        require_once __DIR__ . "/../../cliente_web/resumen.php";
        exit;
    }
}