<!DOCTYPE html>
<html lang="es" data-theme="<?= htmlspecialchars($_COOKIE['cm_tema'] ?? 'light', ENT_QUOTES, 'UTF-8') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil — CineMatch</title>
    <link rel="stylesheet" href="/Assets/css/index.css">
</head>
<body>

<nav class="navbar navbar-user">
    <span class="navbar-brand">🎬 CineMatch</span>
    <div class="navbar-links">
        <a href="/home"   class="nav-link">Inicio</a>
        <a href="/perfil" class="nav-link active">Mi Perfil</a>
        <?php if (($_SESSION['usuario_rol'] ?? '') === 'administrador'): ?>
            <a href="/admin" class="nav-link nav-admin">⚙ Admin</a>
        <?php endif; ?>
    </div>
    <div class="navbar-info">
        <button id="btn-tema" class="btn-tema" title="Cambiar tema">🌙</button>
        <span class="nav-username"><?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
        <form action="/post/usuario/logout" method="POST" style="display:inline">
            <input type="hidden" name="csrf_token"
                   value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" class="btn-logout">Salir</button>
        </form>
    </div>
</nav>

<main class="main-content">
<div class="contenedor perfil-contenedor">

    <h2>👤 Mi Perfil</h2>

    <?php if (!empty($_SESSION['user_logs'])): ?>
        <?php foreach ($_SESSION['user_logs'] as $log): ?>
            <div class="mensaje mensaje-ok"><?= htmlspecialchars($log, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endforeach; unset($_SESSION['user_logs']); ?>
    <?php endif; ?>

    <div class="perfil-datos">
        <div class="dato-item">
            <span class="dato-label">Usuario</span>
            <span><?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <div class="dato-item">
            <span class="dato-label">Rol</span>
            <span><?= htmlspecialchars($_SESSION['usuario_rol'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
        </div>
    </div>

    <hr class="perfil-divisor">

    <h3>🎭 Mis géneros favoritos</h3>
    <p class="intro-form">Selecciona los géneros que más te gustan para recibir recomendaciones personalizadas.</p>

    <form action="/post/preferencias/guardar" method="POST">
        <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <div class="checkboxes-genero">
            <?php foreach ($generos as $g): ?>
                <label class="checkbox-label <?= in_array((int)$g['id'], $preferencias, true) ? 'checked' : '' ?>">
                    <input type="checkbox" name="generos[]" value="<?= (int)$g['id'] ?>"
                           <?= in_array((int)$g['id'], $preferencias, true) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($g['nombre'], ENT_QUOTES, 'UTF-8') ?>
                </label>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="btn-primario" style="max-width:300px;margin-top:20px">
            💾 Guardar preferencias
        </button>
    </form>

    <!-- Últimas vistas desde cookie -->
    <?php
    $ultimasVistas = [];
    if (!empty($_COOKIE['ultimas_vistas'])) {
        $decoded = json_decode(base64_decode($_COOKIE['ultimas_vistas']), true);
        if (is_array($decoded)) $ultimasVistas = $decoded;
    }
    ?>
    <?php if (!empty($ultimasVistas)): ?>
        <hr class="perfil-divisor">
        <h3>🕓 Últimas vistas</h3>
        <ul class="lista-ultimas">
            <?php foreach ($ultimasVistas as $uv): ?>
                <li>
                    <a href="/pelicula/detalle?id=<?= (int)$uv['id'] ?>">
                        <?= htmlspecialchars($uv['titulo'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</div>
</main>

<footer class="site-footer">
    &copy; <?= date('Y') ?> CineMatch — DS7 Grupo 5
</footer>

<script src="/Assets/js/home.js"></script>
</body>
</html>
