<?php
require_once 'Procesar.php';
$procesar = new Procesar();

$datos = $procesar->procesarGet(
    $_GET['nombre'], 
    $_GET['Peso'], 
    $_GET['altura']
    );
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
</head>
<body>
    <h1>Resultado del formulario GET</h1>
    <p><strong>Nombre:</strong> <?php echo $datos['nombre']; ?></p>
    <p><strong>Peso:</strong> <?php echo $datos['peso']; ?> kg</p>
    <p><strong>Altura:</strong> <?php echo $datos['altura']; ?> m</p>
    <p><strong>IMC:</strong> <?php echo number_format($datos['imc'], 2); ?></p>

    <hr>

    <h3> Variables $_SERVER</h3>
    <P>PHP_SELF: <?php echo $_SERVER['PHP_SELF']; ?></P>
    <P>SERVER_NAME: <?php echo $_SERVER['SERVER_NAME']; ?></P>
    <P>HTTP_USER_AGENT: <?php echo $_SERVER['HTTP_USER_AGENT']; ?></P>
    <P>REQUEST_METHOD: <?php echo $_SERVER['REQUEST_METHOD']; ?></P>
    <p>REMOTE_ADDR: <?php echo $_SERVER['REMOTE_ADDR']; ?></p>
    <P>QUERY_STRING: <?php echo $_SERVER['QUERY_STRING']; ?></P>

    <a href="formG.php">Volver al formulario GET</a>
</body>
</html>