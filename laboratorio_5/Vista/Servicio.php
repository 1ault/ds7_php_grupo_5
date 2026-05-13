<form action="/api/servicio/buy" method="POST">

    <label>Servicios</label>
    <label>Nombre: 
        <?= htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') ?>
    </label>

    <?php foreach ($servicios as $id => $servicio): ?>
        <label class="services">
            <input type="checkbox" name="services[]" value="<?= htmlspecialchars($servicio['id'], ENT_QUOTES, 'UTF-8') ?>">
            <span class="services-info">
                <span class="services-name">
                <?= htmlspecialchars($servicio['name'], ENT_QUOTES, 'UTF-8') ?>
                </span>
            </span>
            <span class="services-precioe" data-price="1200">
                <?= htmlspecialchars($servicio['precio'], ENT_QUOTES, 'UTF-8') ?>
            </span>
        </label>
    <?php endforeach ?>


    <input 
        type="hidden" 
        name="csrf_token" 
        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
    >

    <input 
        type="hidden" 
        name="csrf_token_expiry" 
        value="<?= htmlspecialchars($_SESSION['csrf_token_expiry'], ENT_QUOTES, 'UTF-8') ?>"
    >

    <button type="submit">Pedir</button>

</form>
