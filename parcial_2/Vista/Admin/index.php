<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    <title>Panel Admin — RH System</title>
    <link rel="stylesheet" href="/Assets/css/index.css">
</head>
<body>

<!-- ── Navbar ─────────────────────────────────────────────────────── -->
<nav class="navbar navbar-admin">
    <span class="navbar-brand">⚙ RH System — Panel Admin</span>
    <div class="navbar-info">
        <span>👤 <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
        <a href="/post/usuario/logout" class="btn-logout">Cerrar sesión</a>
    </div>
</nav>

<!-- ── Contenido principal ────────────────────────────────────────── -->
<div class="main-content">
<div class="contenedor admin-contenedor">

    <h2>Solicitudes de Aspirantes</h2>

    <!-- Mensajes flash -->
    <?php if (!empty($_SESSION['user_logs']) && is_array($_SESSION['user_logs'])): ?>
        <?php
            // Detectar si es mensaje de éxito o error
            $esExito = in_array($_SESSION['user_logs'][0] ?? '', [
                'Estado actualizado correctamente.',
                'Solicitud actualizada correctamente.'
            ]);
        ?>
        <?php foreach ($_SESSION['user_logs'] as $log): ?>
            <div class="mensaje <?= $esExito ? 'mensaje-ok' : '' ?>">
                <?= htmlspecialchars($log, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['user_logs']); ?>
    <?php endif; ?>

    <!-- Toast (notificación flotante) -->
    <div id="toast" role="alert" aria-live="assertive"></div>

    <!-- Filtro de búsqueda -->
    <div class="admin-filtros">
        <label for="filtro-buscar">🔍 Buscar:</label>
        <input
            type="text"
            id="filtro-buscar"
            class="filtro-input"
            placeholder="Nombre, apellido, cédula, correo…"
            autocomplete="off">
        <label for="filtro-estado">Estado:</label>
        <select id="filtro-estado">
            <option value="">Todos</option>
            <option value="no revisado">No revisado</option>
            <option value="considerado">Considerado</option>
            <option value="no considerado">No considerado</option>
        </select>
    </div>

    <!-- Tabla -->
    <div class="tabla-wrapper">
        <table class="tabla-admin" id="tabla-aspirantes">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cédula / Pasaporte</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Género</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Estado</th>
                    <th>Cambiar estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($aspirantes)): ?>
                    <tr>
                        <td colspan="9" class="admin-vacio">No hay solicitudes registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($aspirantes as $a): ?>
                    <?php
                        $estado = $a['estado_solicitud'];
                        $clase  = match($estado) {
                            'considerado'    => 'badge-considerado',
                            'no considerado' => 'badge-no-considerado',
                            default          => 'badge-no-revisado',
                        };
                    ?>
                    <tr>
                        <td><?= (int)$a['id'] ?></td>
                        <td><?= htmlspecialchars($a['cedula_pasaporte'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($a['nombre'],           ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($a['apellido'],         ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($a['genero'],           ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($a['telefono'],         ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($a['correo'],           ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <span class="badge <?= $clase ?>" data-estado-badge>
                                <?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>
                        <td>
                            <div class="form-estado-inline">
                                <select class="select-estado-inline" data-select-estado>
                                    <option value="no revisado"    <?= $estado === 'no revisado'    ? 'selected' : '' ?>>No revisado</option>
                                    <option value="considerado"    <?= $estado === 'considerado'    ? 'selected' : '' ?>>Considerado</option>
                                    <option value="no considerado" <?= $estado === 'no considerado' ? 'selected' : '' ?>>No considerado</option>
                                </select>
                                <button
                                    class="btn-guardar-estado"
                                    data-uid="<?= (int)$a['usuario_id'] ?>">
                                    Guardar
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <p class="admin-total">Total: <?= count($aspirantes) ?> solicitud(es)</p>

</div>
</div>

<!-- ── Footer ─────────────────────────────────────────────────────── -->
<footer class="site-footer">RH System &copy; <?= date('Y') ?> — DS7 Grupo 5</footer>

<script src="/Assets/js/admin.js"></script>

</body>
</html>
