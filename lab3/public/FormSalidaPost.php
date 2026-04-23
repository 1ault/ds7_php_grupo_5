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
echo "Edad: " . htmlspecialchars($form->getKey("edad"), ENT_QUOTES, "UTF-8") . "<br><br>";

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
