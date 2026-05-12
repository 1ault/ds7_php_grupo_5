<form action="/api/auth/login" method="POST">
    <label for="">Login</label>

    <label for="email">Correo:</label>
    <input type="email" id="email" name="email" placeholder="example@local.com" required>

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Entrar</button>
</form>

<a href="/register" class="button-href">Registrarse</a>

<?php if (!empty($logs)): ?>
    <ul class="log">
        <?php foreach ($logs as $log): ?>
            <li><?= htmlspecialchars($log, ENT_QUOTES, "UTF-8"); ?></li>

        <?php endforeach ?>
    </ul>
<?php endif ?>
