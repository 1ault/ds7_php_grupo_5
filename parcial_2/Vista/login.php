<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="/Assets/css/index.css">
</head>
<body>

<div class="contenedor">

    <h2>Iniciar Sesión</h2>

    <?php if(!empty($mensaje)): ?>

        <div class="mensaje">
            <?= htmlspecialchars($mensaje) ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="grupo">
            <label>Usuario</label>
            <input type="text" name="usuario" pattern="^[a-zA-Z0-9_]{5,15}$" maxlength="15" required>
        </div>

        <div class="grupo">
            <label>Contraseña</label>
            <input type="password" name="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{15,}$" maxlength="20" required>
        </div>

        <button type="submit">
            Iniciar Sesión
        </button>

    </form>

</div>

</body>
</html>