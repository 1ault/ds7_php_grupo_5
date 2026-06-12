<?php
// Partial: card_pelicula.php
// Variables: $p (id, titulo, tipo, anio, poster_url, generos)
?>
<article class="card-pelicula"
         data-titulo="<?= htmlspecialchars($p['titulo']  ?? '', ENT_QUOTES, 'UTF-8') ?>"
         data-tipo="<?= htmlspecialchars($p['tipo']    ?? '', ENT_QUOTES, 'UTF-8') ?>"
         data-generos="<?= htmlspecialchars($p['generos'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    <a href="/pelicula/detalle?id=<?= (int)$p['id'] ?>" class="card-link">
        <?php if (!empty($p['poster_url'])): ?>
            <img src="<?= htmlspecialchars($p['poster_url'], ENT_QUOTES, 'UTF-8') ?>"
                 alt="<?= htmlspecialchars($p['titulo'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                 class="card-poster" loading="lazy"
                 onerror="this.src='/Assets/img/no-poster.svg'">
        <?php else: ?>
            <div class="card-no-poster">🎬</div>
        <?php endif; ?>
        <div class="card-info">
            <span class="card-tipo <?= ($p['tipo'] ?? '') === 'serie' ? 'tipo-serie' : 'tipo-pelicula' ?>">
                <?= ($p['tipo'] ?? '') === 'serie' ? '📺 Serie' : '🎬 Película' ?>
            </span>
            <h3 class="card-titulo"><?= htmlspecialchars($p['titulo'] ?? '', ENT_QUOTES, 'UTF-8') ?></h3>
            <p class="card-anio"><?= (int)($p['anio'] ?? 0) ?></p>
            <?php if (!empty($p['generos'])): ?>
                <p class="card-generos"><?= htmlspecialchars($p['generos'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
        </div>
    </a>
</article>
