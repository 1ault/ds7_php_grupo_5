<?php
class Cookie {

    public function mostrarMensaje() {

       
    if (isset($_COOKIE['nombre'])) {
            echo "Bienvenido, " . $_COOKIE['nombre'];
        }
        else {
            echo "La cookie no existe.";
        }

    }
}

$cookie = new Cookie();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
</head>
<body>

    <h1><?php $cookie->mostrarMensaje(); ?></h1>

    <form action="salir.php" method="POST">
        <button type="submit">Salir</button>
    </form>

</body>
</html>