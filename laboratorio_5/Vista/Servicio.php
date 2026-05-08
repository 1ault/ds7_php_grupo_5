<form action="/servicio" method="POST">
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

<section class="libros">
    <?php if (empty($libros)): ?>
        <span>404 no encontrado</span>
    <?php else: ?>
        <?php foreach ($libros as $libro): ?>

            <article class="libro">
                <img 
                    src="<?= htmlspecialchars($libro['img']) ?>" 
                    alt="<?= htmlspecialchars($libro['nombre']) ?>"
                >
                <span>Nombre: <?= htmlspecialchars($libro['nombre']) ?></span>
                <span>Autor: <?= htmlspecialchars($libro['autor']) ?></span>
                <span>Fecha: <?= htmlspecialchars($libro['fecha']) ?></span>

                <a href="/listar/<?= (int) $libro['id'] ?>">
                    Ver
                </a>
            </article>

        <?php endforeach ?>
    <?php endif ?>
</section>
