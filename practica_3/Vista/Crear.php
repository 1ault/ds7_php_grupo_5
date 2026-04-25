<form action="/libro/crear" method="POST">
    <label for="">Registro de Libro</label>

    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" placeholder="nombre">

    <label for="autor">Autor</label>
    <input type="text" name="autor" placeholder="autor">

    <label for="fecha">Fecha:</label>
    <input type="text" name="fecha">

    <label for="categoria">Categoria:</label>
    <input type="text" name="categoria">

    <label for="img">Portada:</label>
    <input type="text" name="img" placeholder="/Assets/img/name.jpg">

    <button type="submit">Guardar</button>
</form>
<?php if (!empty($logs)): ?>
    <ul class="log">
        <?php foreach ($logs as $log): ?>
            <li><?= htmlspecialchars($log) ?></li>
        <?php endforeach ?>
    </ul>
<?php endif ?>
