<!DOCTYPE html>
<html lang="es" data-theme="<?= htmlspecialchars($_COOKIE['cm_tema'] ?? 'light', ENT_QUOTES, 'UTF-8') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    <title><?= htmlspecialchars($pelicula['titulo'] ?? 'Detalle', ENT_QUOTES, 'UTF-8') ?> — CineMatch</title>
    <link rel="stylesheet" href="/Assets/css/index.css">
</head>
<body>

<nav class="navbar navbar-user">
    <span class="navbar-brand">🎬 CineMatch</span>
    <div class="navbar-links">
        <a href="/home"   class="nav-link">Inicio</a>
        <a href="/perfil" class="nav-link">Mi Perfil</a>
        <?php if (($_SESSION['usuario_rol'] ?? '') === 'administrador'): ?>
            <a href="/admin" class="nav-link nav-admin">⚙ Admin</a>
        <?php endif; ?>
    </div>
    <div class="navbar-info">
        <button id="btn-tema" class="btn-tema" title="Cambiar tema">🌙</button>
        <form action="/post/usuario/logout" method="POST" style="display:inline">
            <input type="hidden" name="csrf_token"
                   value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" class="btn-logout">Salir</button>
        </form>
    </div>
</nav>

<main class="main-content">
    <div class="detalle-wrapper">

        <div class="detalle-poster">
            <?php if (!empty($pelicula['poster_url'])): ?>
                <img src="<?= htmlspecialchars($pelicula['poster_url'], ENT_QUOTES, 'UTF-8') ?>"
                     alt="<?= htmlspecialchars($pelicula['titulo'],     ENT_QUOTES, 'UTF-8') ?>"
                     class="detalle-img"
                     onerror="this.src='/Assets/img/no-poster.svg'">
            <?php else: ?>
                <div class="detalle-no-poster">🎬</div>
            <?php endif; ?>
        </div>

        <div class="detalle-info">
            <span class="card-tipo <?= ($pelicula['tipo'] ?? '') === 'serie' ? 'tipo-serie' : 'tipo-pelicula' ?>">
                <?= ($pelicula['tipo'] ?? '') === 'serie' ? '📺 Serie' : '🎬 Película' ?>
            </span>
            <h1 class="detalle-titulo"><?= htmlspecialchars($pelicula['titulo'], ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="detalle-anio">📅 <?= (int)$pelicula['anio'] ?></p>
            <?php if (!empty($pelicula['generos'])): ?>
                <p class="detalle-generos">🏷 <?= htmlspecialchars($pelicula['generos'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
            <?php if (!empty($pelicula['descripcion'])): ?>
                <p class="detalle-descripcion"><?= htmlspecialchars($pelicula['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <!-- Calificación con estrellas -->
            <div class="calificacion-bloque">
                <h3>Tu calificación</h3>
                <form action="/post/pelicula/calificar" method="POST" class="form-calificar">
                    <input type="hidden" name="csrf_token"
                           value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="pelicula_id" value="<?= (int)$pelicula['id'] ?>">
                    <div class="estrellas">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" name="puntuacion" id="star<?= $i ?>"
                                   value="<?= $i ?>" <?= $calificacion === $i ? 'checked' : '' ?>>
                            <label for="star<?= $i ?>" title="<?= $i ?> estrella<?= $i > 1 ? 's' : '' ?>">★</label>
                        <?php endfor; ?>
                    </div>
                    <button type="submit" class="btn-calificar">Guardar calificación</button>
                </form>
            </div>

            <a href="/home" class="btn-volver">← Volver al catálogo</a>
        </div>

    </div>
</main>

<footer class="site-footer">
    &copy; <?= date('Y') ?> CineMatch — DS7 Grupo 5
</footer>

<script src="/Assets/js/home.js"></script>
</body>
</html>
