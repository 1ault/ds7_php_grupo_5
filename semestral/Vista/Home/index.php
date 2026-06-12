<!DOCTYPE html>
<html lang="es" data-theme="<?= htmlspecialchars($_COOKIE['cm_tema'] ?? 'light', ENT_QUOTES, 'UTF-8') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    <title>CineMatch — Inicio</title>
    <link rel="stylesheet" href="/Assets/css/index.css">
</head>
<body>

<nav class="navbar navbar-user">
    <span class="navbar-brand">🎬 CineMatch</span>
    <div class="navbar-links">
        <a href="/home"   class="nav-link active">Inicio</a>
        <a href="/perfil" class="nav-link">Mi Perfil</a>
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

    <?php if (!empty($_SESSION['user_logs'])): ?>
        <?php foreach ($_SESSION['user_logs'] as $log): ?>
            <div class="mensaje"><?= htmlspecialchars($log, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endforeach; unset($_SESSION['user_logs']); ?>
    <?php endif; ?>

    <!-- Recomendaciones -->
    <section class="seccion">
        <div class="seccion-header">
            <h2>✨ Recomendado para ti</h2>
            <?php if (empty($preferencias)): ?>
                <a href="/perfil" class="btn-link">Configura tus géneros →</a>
            <?php endif; ?>
        </div>

        <?php if (empty($preferencias)): ?>
            <div class="aviso-prefs">
                <p>🎭 Aún no has seleccionado géneros favoritos.</p>
                <a href="/perfil" class="btn-primario btn-sm">Ir a Perfil</a>
            </div>
        <?php elseif (empty($recomendaciones)): ?>
            <p class="texto-vacio">Ya viste todo lo disponible en tus géneros. ¡Explora el catálogo completo!</p>
        <?php else: ?>
            <div class="grid-peliculas">
                <?php foreach ($recomendaciones as $p): ?>
                    <?php include __DIR__ . '/../Partials/card_pelicula.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Catálogo completo -->
    <section class="seccion">
        <h2>🎬 Catálogo completo</h2>

        <div class="filtros-catalogo">
            <input type="text" id="filtro-busqueda" placeholder="🔍 Buscar título…" autocomplete="off">
            <select id="filtro-tipo">
                <option value="">Todo</option>
                <option value="pelicula">Películas</option>
                <option value="serie">Series</option>
            </select>
            <select id="filtro-genero">
                <option value="">Todos los géneros</option>
                <?php foreach ($generos as $g): ?>
                    <option value="<?= htmlspecialchars($g['nombre'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($g['nombre'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button id="btn-filtrar" class="btn-filtrar">Filtrar</button>
        </div>

        <div class="grid-peliculas" id="grid-catalogo">
            <?php foreach ($todas as $p): ?>
                <?php include __DIR__ . '/../Partials/card_pelicula.php'; ?>
            <?php endforeach; ?>
        </div>
        <p id="sin-resultados" class="texto-vacio" style="display:none">No se encontraron resultados.</p>
    </section>

    <!-- Historial -->
    <?php if (!empty($historial)): ?>
    <section class="seccion">
        <h2>🕓 Visto recientemente</h2>
        <div class="grid-peliculas">
            <?php foreach ($historial as $p): ?>
                <?php include __DIR__ . '/../Partials/card_pelicula.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

</main>

<footer class="site-footer">
    &copy; <?= date('Y') ?> CineMatch — DS7 Grupo 5 — Universidad Tecnológica de Panamá
</footer>

<script src="/Assets/js/home.js"></script>
</body>
</html>
