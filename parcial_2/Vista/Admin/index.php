<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - RH</title>
    <link rel="stylesheet" href="/Assets/css/index.css">
    <style>
        main {
            width: 100%;
            max-width: 1100px;
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #0a2342;
        }

        .tabla-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        thead {
            background-color: #1a4f8a;
            color: white;
        }

        thead th {
            padding: 0.75rem 1rem;
            text-align: left;
            white-space: nowrap;
        }

        tbody tr:nth-child(even) {
            background-color: #f0f4f9;
        }

        tbody tr:hover {
            background-color: #dce8f7;
        }

        tbody td {
            padding: 0.65rem 1rem;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
            white-space: nowrap;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.65rem;
            border-radius: 1rem;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: capitalize;
        }

        .badge-no-revisado { background-color: #f0ad4e; color: #fff; }
        .badge-considerado { background-color: #5cb85c; color: #fff; }
        .badge-no-considerado { background-color: #d9534f; color: #fff; }

        .acciones {
            display: flex;
            gap: 0.5rem;
        }

        .btn-considerado, .btn-no-considerado {
            padding: 0.35rem 0.75rem;
            border: none;
            border-radius: 0.3rem;
            font-size: 0.85rem;
            font-weight: bold;
            cursor: pointer;
            color: white;
        }

        .btn-considerado     { background-color: #5cb85c; }
        .btn-considerado:hover { background-color: #449d44; }

        .btn-no-considerado  { background-color: #d9534f; }
        .btn-no-considerado:hover { background-color: #c9302c; }

        .sin-aspirantes {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-style: italic;
        }

        #toast {
            display: none;
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background-color: #1a4f8a;
            color: white;
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 999;
        }
    </style>
</head>
<body>

<main>
    <h2>Panel de Administración — Solicitudes</h2>

    <?php if (!empty($_SESSION['user_logs']) && is_array($_SESSION['user_logs'])): ?>
        <?php foreach ($_SESSION['user_logs'] as $log): ?>
            <div class="mensaje">
                <?= htmlspecialchars($log, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['user_logs']); ?>
    <?php endif; ?>

    <div class="tabla-wrapper">
        <table>
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
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($aspirantes)): ?>
                    <tr>
                        <td colspan="9" class="sin-aspirantes">No hay solicitudes registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($aspirantes as $a): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)$a['id'],            ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($a['cedula_pasaporte'],       ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($a['nombre'],                 ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($a['apellido'],               ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($a['genero'],                 ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($a['telefono'],               ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($a['correo'],                 ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <?php
                                $estado = $a['estado_solicitud'];
                                $clase  = match($estado) {
                                    'considerado'    => 'badge-considerado',
                                    'no considerado' => 'badge-no-considerado',
                                    default          => 'badge-no-revisado',
                                };
                            ?>
                            <span class="badge <?= $clase ?>">
                                <?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>
                        <td>
                            <div class="acciones">
                                <button
                                    class="btn-considerado"
                                    onclick="actualizarEstado(<?= (int)$a['usuario_id'] ?>, 'considerado', this)">
                                    Considerado
                                </button>
                                <button
                                    class="btn-no-considerado"
                                    onclick="actualizarEstado(<?= (int)$a['usuario_id'] ?>, 'no considerado', this)">
                                    No Considerado
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<div id="toast"></div>

<script>
    function mostrarToast(mensaje, exito = true) {
        const toast = document.getElementById('toast');
        toast.textContent = mensaje;
        toast.style.backgroundColor = exito ? '#1a4f8a' : '#d9534f';
        toast.style.display = 'block';
        setTimeout(() => { toast.style.display = 'none'; }, 3000);
    }

    function actualizarEstado(usuario_id, estado, boton) {
        const fila    = boton.closest('tr');
        const badge   = fila.querySelector('.badge');
        const botones = fila.querySelectorAll('button');

        botones.forEach(b => b.disabled = true);

        fetch('/api/admin/update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `usuario_id=${usuario_id}&estado_solicitud=${encodeURIComponent(estado)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                const clases = {
                    'considerado':    'badge-considerado',
                    'no considerado': 'badge-no-considerado',
                    'no revisado':    'badge-no-revisado'
                };
                badge.className   = 'badge ' + (clases[estado] ?? 'badge-no-revisado');
                badge.textContent = estado.trim();
                mostrarToast('Estado actualizado correctamente.', true);
            } else {
                mostrarToast('Error al actualizar.', false);
            }
        })
        .catch(() => {
            mostrarToast('Error de conexión.', false);
        })
        .finally(() => {
            botones.forEach(b => b.disabled = false);
        });
    }
</script>

</body>
</html>
