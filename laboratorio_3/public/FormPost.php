<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lab3</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <form action="FormApi.php" method="POST">
        <label for="">Formulario Registro de contacto</label>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre">

        <label for="correo">Correo</label>
        <input type="text" name="correo">

        <label for="cedula">Cedula:</label>
        <input type="text" name="cedula">

        <label for="edad">Edad:</label>
        <input type="text" name="edad">

        <button type="submit">Buscar</button>
    </form>

</body>
</html>
