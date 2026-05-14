<?php
setcookie("nombre", $_POST['nombre'], time() + (60 * 5), "/");
header("Location: bienvenida.php");
exit;

?>