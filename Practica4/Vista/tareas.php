<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Aspirante</title>
    <link rel="stylesheet" href="/Assets/css/index.css">
</head>
<body>

<div class="contenedor">

    <h2>Registro de Tareas</h2>

    <?php if(!empty($_SESSION['user_logs']) && is_array($_SESSION['user_logs'])): ?>
        <?php foreach ($_SESSION['user_logs'] as $log): ?>
        <div class="mensaje">
            <?= htmlspecialchars($log, ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <?php endforeach ?>
        <?php unset($_SESSION['user_logs']); ?> 
    <?php endif; ?>

    <form action="/post/tarea/guardar" method="POST"> 
        <div class="grupo">
            <label for="tarea">Tarea</label>
            <input 
                type="text" 
                name="tarea" 
                id="tarea" 
                placeholder="Ingrese su tarea" 
                pattern="^[a-zA-Z0-9_ ]{2,100}$" 
                minlength="2" 
                maxlength="100">
                <button type="submit">Guardar Tarea</button>
 </form>
 <table>
    <thead>
        <tr>
            <th>Tarea</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (isset($tareas) && is_array($tareas)): ?>
        <?php foreach ($tareas as $index => $tarea): ?>
      
            <tr>
            <?php if (isset($_GET['editar']) && (int)$_GET['editar'] === $index): ?>
            <form action="/post/tarea/editar" method="POST">
            <input type="hidden" name="index" value="<?= $index ?>">
        <td>
            <input type="text" name="tarea" value="<?= htmlspecialchars($tarea['tarea']) ?>">
        </td>

        <td>
            <select name="estado">
                <option value="Pendiente"
                    <?= $tarea['estado'] === 'Pendiente' ? 'selected' : '' ?>>
                    Pendiente
                </option>

                <option value="Completada"
                    <?= $tarea['estado'] === 'Completada' ? 'selected' : '' ?>>
                    Completada
                </option>
            </select>
        </td>

        <td>
            <button type="submit">Guardar</button>
        </td>

    </form>

<?php else: ?>

    <td><?= htmlspecialchars($tarea['tarea']) ?></td>
    <td><?= htmlspecialchars($tarea['estado']) ?></td>

    <td>
        <a href="/tareas?editar=<?= $index ?>" class="editar"> Editar</a>

        <form action="/post/tarea/eliminar" method="POST" style="display:inline;">
            <input type="hidden" name="index" value="<?= $index; ?>">
            <button class="eliminar" type="submit">Eliminar</button>
        </form>
    </td>

<?php endif; ?>
</tr>
<?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</div>
</body>
</html>
