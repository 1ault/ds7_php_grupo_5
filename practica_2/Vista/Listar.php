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
