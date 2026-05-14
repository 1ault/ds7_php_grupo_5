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
    exit("altura inválida");
}

if ($altura <= 0) {
    exit("altura debe ser mayor a 0");
}

$peso = filter_var($form->getKey("peso"), FILTER_VALIDATE_FLOAT);
if ($peso === false) {
    exit("peso inválido");
}

if ($peso <= 0) {
    exit("peso debe ser mayor a 0");
}

echo "Nombre: " . htmlspecialchars($form->getKey("nombre"), ENT_QUOTES, "UTF-8") . "<br>";
echo "Peso: " . htmlspecialchars($form->getKey("peso"), ENT_QUOTES, "UTF-8") . "<br>";
echo "Altura: " . htmlspecialchars($form->getKey("altura"), ENT_QUOTES, "UTF-8") . "<br>";
echo "IMC = " . $peso / ($altura * $altura) . "<br><br>";

echo "PHP Self: " . $_SERVER['PHP_SELF'] . "<br>";
echo "Server Name: " . $_SERVER['SERVER_NAME'] . "<br>";
echo "HTTP Host: " . $_SERVER['HTTP_USER_AGENT'] . "<br>";
echo "Request Method: " . $_SERVER['REQUEST_METHOD'] . "<br>";
echo "Remote Addr: " . $_SERVER['REMOTE_ADDR'] . "<br>";
echo "Query String: " . $_SERVER['QUERY_STRING'] . "<br>";

?>
</form>

</body>
</html>


