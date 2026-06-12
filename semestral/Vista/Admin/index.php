<!DOCTYPE html>
<html lang="es" data-theme="<?= htmlspecialchars($_COOKIE['cm_tema'] ?? 'light', ENT_QUOTES, 'UTF-8') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    <title>Panel Administrador — CineMatch</title>
    <link rel="stylesheet" href="/Assets/css/index.css">
</head>
<body>

<nav class="navbar navbar-admin">
    <span class="navbar-brand">⚙ CineMatch — Admin</span>
    <div class="navbar-links">
        <a href="/home" class="nav-link">Ver sitio</a>
    </div>
    <div class="navbar-info">
        <span class="nav-username"><?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
        <form action="/post/usuario/logout" method="POST" style="display:inline">
            <input type="hidden" name="csrf_token"
                   value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" class="btn-logout">Salir</button>
        </form>
    </div>
</nav>

<main class="main-content">
<div class="contenedor admin-contenedor">

    <h2>🎬 Gestión de Películas y Series</h2>

    <?php if (!empty($_SESSION['user_logs'])): ?>
        <?php foreach ($_SESSION['user_logs'] as $log): ?>
            <div class="mensaje mensaje-ok"><?= htmlspecialchars($log, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endforeach; unset($_SESSION['user_logs']); ?>
    <?php endif; ?>

    <!-- Importar webservices -->
    <div class="admin-importar">
        <h3>📥 Importar catálogo desde Webservices</h3>
        <div class="importar-botones">
            <button class="btn-importar" onclick="importar('xml')">📄 Importar desde XML</button>
            <button class="btn-importar" onclick="importar('json')">📋 Importar desde JSON</button>
        </div>
        <div id="importar-resultado" class="importar-resultado" style="display:none"></div>
    </div>

    <!-- Estadísticas géneros más visitados -->
    <div class="admin-stats">
        <h3>📊 Géneros más visitados por los usuarios</h3>
        <?php if (empty($estadisticas)): ?>
            <p class="texto-vacio">Sin datos de visitas aún.</p>
        <?php else: ?>
            <div class="stats-bars">
                <?php
                $max = max(array_column($estadisticas, 'visitas'));
                foreach ($estadisticas as $s):
                    $pct = $max > 0 ? round(($s['visitas'] / $max) * 100) : 0;
                ?>
                <div class="stat-row">
                    <span class="stat-label"><?= htmlspecialchars($s['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
                    <div class="stat-bar-wrapper">
                        <div class="stat-bar" style="width:<?= $pct ?>%"></div>
                    </div>
                    <span class="stat-count"><?= (int)$s['visitas'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Formulario agregar / editar -->
    <div class="admin-form-wrapper">
        <h3 id="form-titulo-label">➕ Agregar Película / Serie</h3>
        <form action="/post/admin/pelicula/crear" method="POST" id="form-pelicula">
            <input type="hidden" name="csrf_token"
                   value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="id" id="form-id" value="">

            <div class="grupo-fila">
                <div class="grupo">
                    <label>Título <span class="requerido">*</span></label>
                    <input type="text" name="titulo" id="form-titulo" maxlength="255" required>
                </div>
                <div class="grupo">
                    <label>Tipo <span class="requerido">*</span></label>
                    <select name="tipo" id="form-tipo" required>
                        <option value="pelicula">Película</option>
                        <option value="serie">Serie</option>
                    </select>
                </div>
            </div>

            <div class="grupo-fila">
                <div class="grupo">
                    <label>Año <span class="requerido">*</span></label>
                    <input type="number" name="anio" id="form-anio"
                           min="1888" max="<?= date('Y') + 2 ?>" required>
                </div>
                <div class="grupo">
                    <label>URL del Poster</label>
                    <input type="url" name="poster_url" id="form-poster"
                           maxlength="500" placeholder="https://…">
                </div>
            </div>

            <div class="grupo">
                <label>Descripción</label>
                <textarea name="descripcion" id="form-descripcion" rows="3" maxlength="1000"></textarea>
            </div>

            <div class="grupo">
                <label>Géneros</label>
                <div class="checkboxes-genero checkboxes-small">
                    <?php foreach ($generos as $g): ?>
                        <label class="checkbox-label">
                            <input type="checkbox" name="generos[]"
                                   value="<?= (int)$g['id'] ?>"
                                   class="chk-genero-form"
                                   data-genero-id="<?= (int)$g['id'] ?>">
                            <?= htmlspecialchars($g['nombre'], ENT_QUOTES, 'UTF-8') ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div style="display:flex;gap:12px;flex-wrap:wrap">
                <button type="submit" class="btn-primario" style="max-width:220px" id="btn-form-submit">
                    💾 Guardar
                </button>
                <button type="button" class="btn-secundario" style="max-width:180px" onclick="cancelarEdicion()">
                    ✖ Cancelar
                </button>
            </div>
        </form>
    </div>

    <!-- Tabla catálogo -->
    <h3 style="margin-top:32px">📋 Catálogo actual (<?= count($peliculas) ?> títulos)</h3>

    <div class="tabla-wrapper">
        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Poster</th>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th>Año</th>
                    <th>Géneros</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($peliculas)): ?>
                    <tr><td colspan="7" class="admin-vacio">No hay títulos en el catálogo.</td></tr>
                <?php else: ?>
                    <?php foreach ($peliculas as $p): ?>
                    <tr>
                        <td><?= (int)$p['id'] ?></td>
                        <td>
                            <?php if (!empty($p['poster_url'])): ?>
                                <img src="<?= htmlspecialchars($p['poster_url'], ENT_QUOTES, 'UTF-8') ?>"
                                     alt="" class="tabla-poster"
                                     onerror="this.style.display='none'">
                            <?php else: ?><span>—</span><?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p['titulo'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $p['tipo'] === 'serie' ? '📺 Serie' : '🎬 Película' ?></td>
                        <td><?= (int)$p['anio'] ?></td>
                        <td><?= htmlspecialchars($p['generos'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <button class="btn-editar" onclick="editarPelicula(
                                <?= (int)$p['id'] ?>,
                                <?= json_encode($p['titulo']) ?>,
                                '<?= htmlspecialchars($p['tipo'], ENT_QUOTES, 'UTF-8') ?>',
                                <?= (int)$p['anio'] ?>,
                                <?= json_encode($p['poster_url'] ?? '') ?>,
                                <?= json_encode($p['descripcion'] ?? '') ?>,
                                <?= json_encode($p['genero_ids'] ?? '') ?>
                            )">✏ Editar</button>

                            <form action="/post/admin/pelicula/eliminar"
                                  method="POST" style="display:inline"
                                  onsubmit="return confirm('¿Eliminar este título?')">
                                <input type="hidden" name="csrf_token"
                                       value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                <button type="submit" class="btn-eliminar">🗑 Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</main>

<footer class="site-footer">
    &copy; <?= date('Y') ?> CineMatch — DS7 Grupo 5 — Universidad Tecnológica de Panamá
</footer>

<script>
// BASE_URL disponible para el JS del admin
const BASE_URL = '';
</script>
<script src="/Assets/js/admin.js"></script>
</body>
</html>
