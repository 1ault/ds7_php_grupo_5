<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SISTEMA DE PEDIDOS</title>
</head>

<body>

<h1>Veterinaria Patitas</h1>

<form method="post" action="/facturar">

    <table border="1" cellpadding="8" id="tablaProductos">
        <thead>
            <tr>
                <th>ID Producto</th>
                <th>Cantidad</th>
                <th>Acción</th>
            </tr>
        </thead>

        <tbody id="productosBody">
            <tr>
                <td>
                    <input type="number" name="producto_id[]" placeholder="ID" required>
                </td>
                <td>
                    <input type="number" name="cantidad[]" placeholder="Cantidad" min="1" required>
                </td>
                <td>
                    <button type="button" onclick="eliminarFila(this)">Eliminar</button>
                </td>
            </tr>
        </tbody>
    </table>

    <br>

    <button type="button" onclick="agregarProducto()">Agregar Producto</button>
    <button type="submit" name="facturar">Facturar</button>

</form>

<script>
function agregarProducto() {
    let tabla = document.getElementById("productosBody");

    let fila = document.createElement("tr");

    fila.innerHTML = `
        <td>
            <input type="number" name="producto_id[]" placeholder="ID" required>
        </td>
        <td>
            <input type="number" name="cantidad[]" placeholder="Cantidad" min="1" required>
        </td>
        <td>
            <button type="button" onclick="eliminarFila(this)">Eliminar</button>
        </td>
    `;

    tabla.appendChild(fila);
}

function eliminarFila(boton) {
    let tabla = document.getElementById("productosBody");

    if (tabla.rows.length > 1) {
        boton.parentElement.parentElement.remove();
    } else {
        alert("Debe quedar al menos un producto.");
    }
}
</script>

</body>
</html>