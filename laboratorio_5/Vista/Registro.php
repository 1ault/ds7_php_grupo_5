<form action="/registro" method="POST">
    <label for="">Registro</label>

    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" placeholder="nombre">

    <label for="contrasena">Contrasena</label>
    <input type="text" name="autor" placeholder="contrasena">
</form>
<?php if (!empty($logs)): ?>
    <ul class="log">
        <?php foreach ($logs as $log): ?>
            <li><?= htmlspecialchars($log) ?></li>
        <?php endforeach ?>
    </ul>
<?php endif ?>


