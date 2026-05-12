<form action="/api/servicio/buy" method="POST">

    <label>Servicios</label>


    <?php foreach ($servicios as $id => $servicio): ?>
        <?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>
        <?= htmlspecialchars($servicio['name'], ENT_QUOTES, 'UTF-8') ?>
        <?= htmlspecialchars($servicio['precio'], ENT_QUOTES, 'UTF-8') ?>
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

    <button type="submit">Submit</button>

</form>
