<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - RH</title>
    <link rel="stylesheet" href="/Assets/css/index.css">
</head>
<body>
<div class="contenedor">

    <h2>Iniciar Sesión</h2>

    <?php if(!empty($_SESSION['user_logs']) && is_array($_SESSION['user_logs'])): ?>
        <?php foreach ($_SESSION['user_logs'] as $log): ?>
        <div class="mensaje">
            <?= htmlspecialchars($log, ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <?php endforeach ?>
        <?php unset($_SESSION['user_logs']); ?> 
    <?php endif; ?>

    <form action="/post/usuario/login" method="POST">
        <div class="grupo">
            <label for="usuario">Usuario</label>
            <input 
                type="text" 
                name="usuario" 
                id="usuario" 
                placeholder="Ingrese su usuario" 
                pattern="^[a-zA-Z0-9_]{2,20}$" 
                minlength="2" 
                maxlength="20" 
                required
                >
        </div>

        <div class="grupo">
            <label for="password">Contraseña</label>
            <input 
                type="password" 
                name="password" 
                id="password" 
                placeholder="Ingrese su contraseña" 
                minlength="15" 
                maxlength="128"
                required
            >
            <div class="info-password">
                Debe contener mínimo 15 caracteres,
                mayúsculas, minúsculas, números y caracteres especiales.
            </div>
        </div>

        <button type="submit">
            Iniciar Sesión
        </button>

    </form>

    <div class="login-link">
        ¿No tienes cuenta?
        <a href="/registro">Registrarse</a>
    </div>

</div>

</body>
</html>
