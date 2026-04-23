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
$altura = filter_var($form->getKey("altura"), FILTER_VALIDATE_FLOAT);
if ($altura === false) {
    $altura = 0;
}

$peso = filter_var($form->getKey("peso"), FILTER_VALIDATE_FLOAT);
if ($peso === false) {
    $peso = 0;
}

echo "Nombre: " . htmlspecialchars($form->getKey("nombre"), ENT_QUOTES, "UTF-8") . "<br>";
echo "Peso: " . htmlspecialchars($form->getKey("peso"), ENT_QUOTES, "UTF-8") . "<br>";
echo "Altura: " . htmlspecialchars($form->getKey("altura"), ENT_QUOTES, "UTF-8") . "<br>";
echo "IMC = " . $peso / ($altura * $altura);
?>
</form>

</body>
</html>


