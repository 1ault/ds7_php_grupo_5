<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Bienvenida</title>
</head>
<body>

    <h1>Bienvenido Forms Views</h1>
    <p>Ingresa tu nombre:</p>

    <form action="guardar.php" method="POST">
        <input type="text" name="nombre" required>
        <button type="submit">Guardar</button>
    </form>

</body>
</html>