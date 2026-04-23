<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lab3</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

<form>
<?php

echo "Nombre: " . htmlspecialchars($form->getKey("nombre"), ENT_QUOTES, "UTF-8") . "<br>";
echo "Correo: " . htmlspecialchars($form->getKey("correo"), ENT_QUOTES, "UTF-8") . "<br>";
echo "Cedula: " . htmlspecialchars($form->getKey("cedula"), ENT_QUOTES, "UTF-8") . "<br>";
echo "Edad: " . htmlspecialchars($form->getKey("edad"), ENT_QUOTES, "UTF-8") . "<br>";

?>
</form>

</body>
</html>
