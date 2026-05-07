
<form>

    <?php if (!isset($_COOKIE["login"])): ?> 
        <p>No ha iniciado sesion</p>
        <a href="/" class="button-href">Salir</a>
    <?php endif; ?>

    <?php if (isset($_COOKIE["login"])): ?> 
        <p>Bienvenido: <?= htmlspecialchars($login) ?></p>
        <a href="/cookie/free" class="button-href">Salir</a>
    <?php endif; ?>

</form>

