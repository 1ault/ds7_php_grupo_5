<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - RH</title>
    <link rel="stylesheet" href="/Assets/css/index.css">
</head>
<body>

    <div class="contenedor">

        <h2>Crear Cuenta</h2>

        <?php if(!empty($mensaje)): ?>

    <div class="mensaje"> <?= $mensaje ?>
    </div>

     <?php endif; ?>

        <form action="" method="POST">

            <div class="grupo">
                <label for="usuario">Usuario</label>
                <input type="text"name="usuario"id="usuario" placeholder="Ingrese su usuario" pattern="^[a-zA-Z0-9_]{5,15}$" maxlength="15" required>
            </div>

            <div class="grupo">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Ingrese su contraseña" required
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{15,}$" maxlength="20"  >

                <div class="info-password">
                    Debe contener mínimo 15 caracteres,
                    mayúsculas, minúsculas, números y caracteres especiales.
                </div>
            </div>

            <button type="submit">
                Registrarse
            </button>

        </form>

        <div class="login-link">
            ¿Ya tienes cuenta?
            <a href="loginP.php">Iniciar sesión</a>
        </div>

    </div>

</body>
</html>
