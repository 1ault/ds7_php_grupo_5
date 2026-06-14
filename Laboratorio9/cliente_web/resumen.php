<?php

if (empty($data)) {
    echo "No hay resumen disponible.";
    exit;
}

$pedido = $data["pedido"];
$detalles = $data["detalles"];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen de Pedido</title>
</head>

<body>

<h1>Resumen de Pedido</h1>

<p><strong>Pedido ID:</strong> <?= htmlspecialchars($pedido["id"]) ?></p>
<p><strong>Fecha:</strong> <?= htmlspecialchars($pedido["fecha"]) ?></p>

<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>ID Producto</th>
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($detalles as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['producto_id']) ?></td>
            <td><?= htmlspecialchars($item['nombre_producto']) ?></td>
            <td><?= htmlspecialchars($item['cantidad']) ?></td>
            <td>$<?= number_format((float)$item['precio'], 2) ?></td>
            <td>$<?= number_format((float)$item['subtotal'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>

    <tfoot>
        <tr>
            <td colspan="4" align="right">
                <strong>Total:</strong>
            </td>
            <td>
                <strong>$<?= number_format((float)$pedido['total'], 2) ?></strong>
            </td>
        </tr>
    </tfoot>
</table>

<br>

<a href="/test">Volver</a>

</body>
</html>