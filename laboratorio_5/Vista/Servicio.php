<style>
  .srv-wrap {
    max-width: 560px;
    margin: 2.5rem auto;
    padding: 0 1rem;
    font-family: system-ui, sans-serif;
  }
  .srv-header { margin-bottom: 2rem; }
  .srv-header h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 .25rem;
    letter-spacing: -.02em;
  }
  .srv-header p { font-size: .9rem; color: #64748b; margin: 0; }
  .srv-header span { color: #0f172a; font-weight: 600; }

<<<<<<< HEAD
  .srv-list {
    display: flex;
    flex-direction: column;
    gap: .75rem;
    margin-bottom: 1.5rem;
  }

  .srv-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    transition: border-color .15s, background .15s;
    user-select: none;
  }
  
  .srv-item:hover { border-color: #94a3b8; background: #f8fafc; }
  .srv-item.checked { border-color: #0ea5e9; background: #f0f9ff; }

  .srv-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #0ea5e9;
    cursor: pointer;
    flex-shrink: 0;
  }

  .srv-item-body { flex: 1; }
  .srv-item-name {
    font-size: .95rem;
    font-weight: 600;
    color: #0f172a;
  }
  .srv-item-precio {
    margin-left: auto;
    font-size: .95rem;
    font-weight: 700;
    color: #0369a1;
    white-space: nowrap;
  }
=======
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
>>>>>>> 10dbe6672e8d40c6da9f2e6cb0af4110a216a764

  .srv-submit {
    width: 100%;
    padding: .85rem;
    background: #0ea5e9;
    color: #ffffff;
    font-size: 1rem;
    font-weight: 700;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: background .15s, transform .1s;
  }
  .srv-submit:hover { background: #0284c7; }
  .srv-submit:active { transform: scale(.98); }

<<<<<<< HEAD
  .srv-empty { text-align: center; color: #94a3b8; font-size: .9rem; padding: 2rem 0; }
</style>
=======
    <button type="submit">Pedir</button>
>>>>>>> 10dbe6672e8d40c6da9f2e6cb0af4110a216a764

<div class="srv-wrap">
  <div class="srv-header">
    <h1>Selecciona tus servicios</h1>
    <p>Hola, <span><?= htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') ?></span> — marca los servicios que deseas pedir.</p>
  </div>

  <form action="/api/servicio/buy" method="POST">

    <div class="srv-list">
      <?php if (empty($servicios)): ?>
        <p class="srv-empty">No hay servicios disponibles.</p>
      <?php else: ?>
        <?php foreach ($servicios as $servicio): ?>
          <label class="srv-item">
            <input
              type="checkbox"
              name="services[]"
              value="<?= htmlspecialchars($servicio['id'], ENT_QUOTES, 'UTF-8') ?>"
              onchange="this.closest('.srv-item').classList.toggle('checked', this.checked)"
            >
            <div class="srv-item-body">
              <div class="srv-item-name">
                <?= htmlspecialchars($servicio['name'], ENT_QUOTES, 'UTF-8') ?>
              </div>
            </div>
            <div class="srv-item-precio">
              $<?= htmlspecialchars($servicio['precio'], ENT_QUOTES, 'UTF-8') ?>
            </div>
          </label>
        <?php endforeach ?>
      <?php endif ?>
    </div>

    <input type="hidden" name="csrf_token"
      value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="csrf_token_expiry"
      value="<?= htmlspecialchars($_SESSION['csrf_token_expiry'], ENT_QUOTES, 'UTF-8') ?>">

    <button type="submit" class="srv-submit">Pedir servicios</button>

  </form>
</div>
