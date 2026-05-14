<?php
if (isset($_POST['nombre']) && !empty($_POST['nombre'])) {

    $nombre = $_POST['nombre'];

    
    setcookie("nombre_usuario", $nombre, time() + 300, "/");

    header("Location: bienvenida.php");
    exit();

} else {
    header("Location: index.php");
    exit();
}
?>