<?php
// Verificar si la cookie existe
if (isset($_COOKIE['nombre_usuario'])) {
    $nombre = $_COOKIE['nombre_usuario'];
} else {
    $nombre = null;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenida</title>
</head>
<body>

<?php if ($nombre): ?>

    <h1>Bienvenido, <?php echo $nombre; ?> a Forms views</h1>
     <h2>Tu información a sido guardada correctamente en nuestros formularios</h2>
    
    <a href="salir.php">Salir</a>

<?php else: ?>

    <h1>Ingrese el nombre</h1>

    <!-- Volver al formulario -->
    <a href="index.php">Volver al formulario</a>

<?php endif; ?>

</body>
</html>