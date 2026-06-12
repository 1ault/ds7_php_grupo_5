/**
 * admin.js — CineMatch Panel Administrador
 * DS7 Grupo 5
 */

// BASE_URL se inyecta desde la vista Admin/index.php como variable global

// ── Editar película ──────────────────────────────────────────────────────────

function editarPelicula(id, titulo, tipo, anio, poster, descripcion, generoIds) {
    const form = document.getElementById('form-pelicula');
    if (!form) return;

    form.action = BASE_URL + '/post/admin/pelicula/actualizar';

    document.getElementById('form-id').value          = id;
    document.getElementById('form-titulo').value      = titulo      || '';
    document.getElementById('form-tipo').value        = tipo        || 'pelicula';
    document.getElementById('form-anio').value        = anio        || '';
    document.getElementById('form-poster').value      = poster      || '';
    document.getElementById('form-descripcion').value = descripcion || '';

    const ids = (generoIds || '').toString().split(',').map(v => v.trim());
    document.querySelectorAll('.chk-genero-form').forEach(chk => {
        chk.checked = ids.includes(chk.dataset.generoId);
        const label = chk.closest('.checkbox-label');
        if (label) label.classList.toggle('checked', chk.checked);
    });

    document.getElementById('form-titulo-label').textContent = '✏ Editar Película / Serie';
    document.getElementById('btn-form-submit').textContent   = '💾 Actualizar';

    form.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function cancelarEdicion() {
    const form = document.getElementById('form-pelicula');
    if (!form) return;

    form.action = BASE_URL + '/post/admin/pelicula/crear';
    form.reset();

    document.querySelectorAll('.chk-genero-form').forEach(chk => {
        const label = chk.closest('.checkbox-label');
        if (label) label.classList.remove('checked');
    });

    document.getElementById('form-id').value             = '';
    document.getElementById('form-titulo-label').textContent = '➕ Agregar Película / Serie';
    document.getElementById('btn-form-submit').textContent   = '💾 Guardar';
}

// ── Checkboxes visuales ──────────────────────────────────────────────────────

document.querySelectorAll('.chk-genero-form').forEach(chk => {
    chk.addEventListener('change', function () {
        const label = this.closest('.checkbox-label');
        if (label) label.classList.toggle('checked', this.checked);
    });
});

// ── Importar XML / JSON ──────────────────────────────────────────────────────

async function importar(formato) {
    const resultDiv = document.getElementById('importar-resultado');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const url  = BASE_URL + (formato === 'xml' ? '/api/admin/importar/xml' : '/api/admin/importar/json');

    resultDiv.style.display = 'none';

    try {
        const res  = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'csrf_token=' + encodeURIComponent(csrf),
        });
        const data = await res.json();

        resultDiv.style.display = 'block';

        if (data.ok) {
            resultDiv.style.cssText = 'display:block;background:#d4edda;color:#155724;border-left:4px solid #28a745;padding:10px 14px;border-radius:8px;margin-top:10px;font-size:13px';
            resultDiv.textContent   = '✅ ' + data.ok;
            setTimeout(() => location.reload(), 1500);
        } else {
            resultDiv.style.cssText = 'display:block;background:#fde8ea;color:#721c24;border-left:4px solid #e84040;padding:10px 14px;border-radius:8px;margin-top:10px;font-size:13px';
            resultDiv.textContent   = '❌ ' + (data.err || 'Error desconocido');
        }
    } catch (e) {
        resultDiv.style.cssText = 'display:block;background:#fde8ea;color:#721c24;border-left:4px solid #e84040;padding:10px 14px;border-radius:8px;margin-top:10px;font-size:13px';
        resultDiv.textContent   = '❌ Error de red: ' + e.message;
    }
}
