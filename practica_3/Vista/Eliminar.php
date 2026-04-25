<form action="/libro/eliminar" method="POST">
    <label for="">Eliminar de Libro</label>

    <label for="id">ID:</label>
    <input type="text" name="id" placeholder="id">

    <button type="submit">Eliminar</button>
</form>
<?php if (!empty($logs)): ?>
    <ul class="log">
        <?php foreach ($logs as $log): ?>
            <li><?= htmlspecialchars($log) ?></li>
        <?php endforeach ?>
    </ul>
<?php endif ?>
