<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario POST</title>
</head>
<body>
<form action="salidaP.php" method="POST">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" require><br><br>

    <label for="email">Correo electronico:</label>
    <input type="email" id="correo" name="correo" required><br><br>
    
    <label for="cedula">Cedula:</label>
    <input type="text" id="cedula" name="cedula" required pattern="^([0-9]{1,2}|PE|E|N|PI)-[0-9]{1,4}-[0-9]{1,6}$"
    placeholder="Ej: 00-0000-000000"><br><br>
    
    <label for="edad">Edad:</label>
    <input type="number" id="edad" name="edad" required min="0" max="100"><br><br>
    
    <input type="submit" value="Buscar">
</form>
</body>
</html>