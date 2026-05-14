<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario GET</title>
</head>
<body>
    <form action="salidaG.php" method="GET">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="Peso">Peso</label>
        <input type="number" id="Peso" name="Peso" min="40" max="300" required><br><br>

        <label for="altura">Altura</label>
        <input type="number" step="0.01" id="altura" name="altura" min="1.01" max="2.51" pattern="^\d+(\.\d+)?$" required><br><br>

        <input type="submit" value="Enviar">
    </form>

</body>
</html>