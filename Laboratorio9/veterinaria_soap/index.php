<?php
$cliente = new SoapClient("http://localhost/Laboratorios/Laboratorio9/veterinaria_soap/inventario.wsdl");

$resultado = null;
$productos = [];
$error = "";

try {

    if (isset($_POST['buscar'])) {
        $id = (int)$_POST['id_buscar'];
        $resultado = $cliente->obtenerProducto($id);

    }

    if (isset($_POST['actualizar'])) {
        $id = (int)$_POST['id_actualizar'];
        $cantidad = (int)$_POST['cantidad'];
        $resultado = $cliente->actualizarStock($id, $cantidad);
    }

    if (isset($_POST['listar'])) {
        $productos = $cliente->listarProductos();
    }

} catch (SoapFault $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente SOAP - Inventario Veterinaria</title>
    <!-- Fuente moderna desde Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .producto {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 15px;
            margin-bottom: 10px;
        }
        .producto h2 {
            margin-top: 0;
        }
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <h1>Veterinaria Patitas</h1>
   <form method="post">

    <h3>Buscar producto</h3>
    <input type="number" name="id_buscar" placeholder="ID" pattern="[0-9]+">
    <button type="submit" name="buscar">Buscar</button>

    <hr>

    <h3>Actualizar stock</h3>
    <input type="text" name="id_actualizar" placeholder="ID"   pattern="[0-9]+">
    <input type="text" name="cantidad" placeholder="Cantidad" pattern="-?[0-9]+">
    <button type="submit" name="actualizar">Actualizar</button>

    <hr>

    <button type="submit" name="listar">Listar Productos</button>

</form>
    
<?php if($resultado): ?>

<h2>Resultado</h2>

<p><strong>ID:</strong> <?= $resultado->id ?></p>
<p><strong>Nombre:</strong> <?= $resultado->nombre ?></p>
<p><strong>Precio:</strong> <?= $resultado->precio ?></p>
<p><strong>Stock:</strong> <?= $resultado->stock ?></p>

<?php endif; ?>


<?php if(!empty($productos)): ?>

<table border="1">

<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Precio</th>
    <th>Stock</th>
</tr>

<?php foreach($productos as $producto): ?>

<tr>
    <td><?= $producto->id ?></td>
    <td><?= $producto->nombre ?></td>
    <td><?= $producto->precio ?></td>
    <td><?= $producto->stock ?></td>
</tr>
<?php endforeach; ?>

</table>
<?php endif; ?>

</body>
</html>
    


