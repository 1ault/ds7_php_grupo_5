<?php
require_once ('Procesar.php');

    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $cedula = $_POST["cedula"];
    $edad = $_POST["edad"];

    $procesar = new Procesar();
    $resultado = $procesar->procesarPost($nombre, $correo, $cedula, $edad);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
</head>
<body>
    <h1>Resultado del formulario</h1>
    <p><strong>Nombre:</strong> <?php echo $nombre; ?></p>
    <p><strong>Correo electrónico:</strong> <?php echo $correo; ?></p>
    <p><strong>Cédula:</strong> <?php echo $cedula; ?></p>
    <p><strong>Edad:</strong> <?php echo $edad; ?></p> 

    <a href="FormP.php">Volver al formulario</a>
</body>
</html>