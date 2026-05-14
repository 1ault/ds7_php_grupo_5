<div class="factura-container">

    <h1>Factura</h1>

    <div class="factura-info">
        <p><strong>Cliente:</strong> <?= htmlspecialchars($_SESSION['user_name']) ?></p>
        <p><strong>Correo:</strong> <?= htmlspecialchars($_SESSION['user_email']) ?></p>
        <p><strong>Fecha:</strong> <?= htmlspecialchars($factura['fecha_de_compra']) ?></p>
    </div>

    <table class="factura-table">
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Precio</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($servicios as $servicio): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($servicio['name']) ?>
                    </td>

                    <td>
                        $<?= number_format($servicio['precio'] / 100, 2) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3 class="factura-total">
        Total:
        $<?= number_format($factura['total_a_pagar'] / 100, 2) ?>
    </h3>

</div>